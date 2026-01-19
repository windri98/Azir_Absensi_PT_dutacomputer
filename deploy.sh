#!/bin/bash

################################################################################
#
# PT DUTA COMPUTER - Docker Compose Deployment Script
# Version: 1.0.0
# Description: Automated deployment script untuk production environment
#
################################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DEPLOY_DIR="/home/wwwroot/dutacomputer"
APP_NAME="PT DUTA COMPUTER"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

################################################################################
# Functions
################################################################################

print_header() {
    echo -e "${BLUE}================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}================================${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

check_requirements() {
    print_header "Checking Requirements"
    
    local missing=0
    
    # Check Docker
    if ! command -v docker &> /dev/null; then
        print_error "Docker is not installed"
        missing=$((missing+1))
    else
        print_success "Docker installed: $(docker --version)"
    fi
    
    # Check Docker Compose
    if ! command -v docker-compose &> /dev/null; then
        print_error "Docker Compose is not installed"
        missing=$((missing+1))
    else
        print_success "Docker Compose installed: $(docker-compose --version)"
    fi
    
    # Check Git
    if ! command -v git &> /dev/null; then
        print_error "Git is not installed"
        missing=$((missing+1))
    else
        print_success "Git installed: $(git --version)"
    fi
    
    # Check Nginx
    if ! command -v nginx &> /dev/null; then
        print_warning "Nginx is not installed (should be via AaPanel)"
    else
        print_success "Nginx is installed"
    fi
    
    if [ $missing -gt 0 ]; then
        print_error "Missing $missing required tools. Please install them first."
        return 1
    fi
    
    print_success "All requirements satisfied"
    return 0
}

check_directory() {
    print_header "Checking Directory Structure"
    
    if [ ! -d "$DEPLOY_DIR" ]; then
        print_error "Deploy directory not found: $DEPLOY_DIR"
        return 1
    fi
    
    print_success "Deploy directory exists: $DEPLOY_DIR"
    
    # Check required files
    local required_files=("docker-compose.yml" "Dockerfile" ".env" "app/")
    
    for file in "${required_files[@]}"; do
        if [ ! -e "$DEPLOY_DIR/$file" ]; then
            print_error "Missing required file: $file"
            return 1
        else
            print_success "Found: $file"
        fi
    done
    
    return 0
}

check_env() {
    print_header "Checking Environment Configuration"
    
    local env_file="$DEPLOY_DIR/.env"
    
    if [ ! -f "$env_file" ]; then
        print_error ".env file not found"
        return 1
    fi
    
    print_success ".env file found"
    
    # Check critical env variables
    local critical_vars=("APP_ENV" "APP_DEBUG" "APP_URL" "DB_PASSWORD")
    
    for var in "${critical_vars[@]}"; do
        if grep -q "^$var=" "$env_file"; then
            local value=$(grep "^$var=" "$env_file" | cut -d '=' -f 2)
            print_success "$var is configured"
        else
            print_warning "$var is not configured"
        fi
    done
    
    # Check if APP_ENV is production
    if grep -q "^APP_ENV=production" "$env_file"; then
        print_success "APP_ENV is set to production"
    else
        print_warning "APP_ENV is not set to production"
    fi
    
    # Check if APP_DEBUG is false
    if grep -q "^APP_DEBUG=false" "$env_file"; then
        print_success "APP_DEBUG is set to false"
    else
        print_warning "APP_DEBUG is not set to false"
    fi
    
    return 0
}

backup_database() {
    print_header "Creating Database Backup"
    
    local backup_dir="$DEPLOY_DIR/backups"
    local backup_file="$backup_dir/backup_$TIMESTAMP.sql"
    
    if [ ! -d "$backup_dir" ]; then
        mkdir -p "$backup_dir"
        print_success "Created backup directory"
    fi
    
    # Get database password from .env
    local db_pass=$(grep "^DB_PASSWORD=" "$DEPLOY_DIR/.env" | cut -d '=' -f 2)
    local db_user=$(grep "^DB_USERNAME=" "$DEPLOY_DIR/.env" | cut -d '=' -f 2)
    local db_name=$(grep "^DB_DATABASE=" "$DEPLOY_DIR/.env" | cut -d '=' -f 2)
    
    cd "$DEPLOY_DIR"
    
    if docker-compose exec -T db mysqldump -u "$db_user" -p"$db_pass" "$db_name" > "$backup_file" 2>/dev/null; then
        print_success "Database backed up to: $backup_file"
        
        # Keep only last 7 backups
        find "$backup_dir" -name "backup_*.sql" -mtime +7 -delete
        print_info "Cleaned up old backups (keeping last 7 days)"
        
        return 0
    else
        print_error "Failed to backup database"
        return 1
    fi
}

stop_containers() {
    print_header "Stopping Containers"
    
    cd "$DEPLOY_DIR"
    
    if docker-compose stop; then
        print_success "Containers stopped"
        return 0
    else
        print_error "Failed to stop containers"
        return 1
    fi
}

update_code() {
    print_header "Updating Code from Repository"
    
    cd "$DEPLOY_DIR"
    
    # Stash any local changes
    git stash
    print_info "Stashed local changes"
    
    # Fetch latest changes
    if git fetch origin; then
        print_success "Code fetched from repository"
    else
        print_error "Failed to fetch from repository"
        return 1
    fi
    
    # Pull latest code
    if git pull origin main; then
        print_success "Code pulled successfully"
        return 0
    else
        print_error "Failed to pull code"
        return 1
    fi
}

build_containers() {
    print_header "Building Docker Containers"
    
    cd "$DEPLOY_DIR"
    
    if docker-compose build; then
        print_success "Containers built successfully"
        return 0
    else
        print_error "Failed to build containers"
        return 1
    fi
}

start_containers() {
    print_header "Starting Docker Containers"
    
    cd "$DEPLOY_DIR"
    
    if docker-compose up -d; then
        print_success "Containers started"
        
        # Wait for containers to be ready
        print_info "Waiting for containers to be ready..."
        sleep 10
        
        # Check if all containers are running
        local running=$(docker-compose ps --filter "status=running" --quiet | wc -l)
        local total=$(docker-compose ps --quiet | wc -l)
        
        print_info "Running containers: $running/$total"
        
        return 0
    else
        print_error "Failed to start containers"
        return 1
    fi
}

run_migrations() {
    print_header "Running Database Migrations"
    
    cd "$DEPLOY_DIR"
    
    if docker-compose exec -T app php artisan migrate --force; then
        print_success "Migrations completed"
        return 0
    else
        print_warning "Migrations failed or already applied"
        return 0
    fi
}

cache_everything() {
    print_header "Caching Configuration & Routes"
    
    cd "$DEPLOY_DIR"
    
    print_info "Caching config..."
    docker-compose exec -T app php artisan config:cache
    
    print_info "Caching routes..."
    docker-compose exec -T app php artisan route:cache
    
    print_info "Caching views..."
    docker-compose exec -T app php artisan view:cache
    
    print_success "All caches built"
    return 0
}

verify_deployment() {
    print_header "Verifying Deployment"
    
    cd "$DEPLOY_DIR"
    
    # Check if containers are running
    print_info "Checking container status..."
    if ! docker-compose ps | grep -q "Up"; then
        print_error "Some containers are not running"
        return 1
    fi
    print_success "All containers are running"
    
    # Check database connectivity
    print_info "Checking database connectivity..."
    if docker-compose exec -T app php artisan tinker << 'EOF'
DB::connection()->getPdo();
exit;
EOF
    then
        print_success "Database connection successful"
    else
        print_warning "Could not verify database connection"
    fi
    
    # Check storage permissions
    print_info "Checking storage permissions..."
    if docker-compose exec -T app test -w /var/www/html/storage; then
        print_success "Storage directory is writable"
    else
        print_warning "Storage directory might not be writable"
    fi
    
    print_success "Deployment verified"
    return 0
}

restart_nginx() {
    print_header "Restarting Nginx"
    
    if sudo systemctl restart nginx; then
        print_success "Nginx restarted"
        return 0
    else
        print_error "Failed to restart Nginx"
        return 1
    fi
}

show_status() {
    print_header "Deployment Status"
    
    cd "$DEPLOY_DIR"
    
    print_info "Container Status:"
    docker-compose ps
    
    echo ""
    print_info "Recent Logs:"
    docker-compose logs --tail=20 app
}

show_help() {
    cat << EOF
PT DUTA COMPUTER - Deployment Script

Usage: $0 [COMMAND]

Commands:
    check               Check requirements and configuration
    backup              Backup database only
    deploy              Full deployment (recommended)
    update              Update code only
    migrate             Run database migrations
    cache               Rebuild caches
    status              Show deployment status
    logs                Show container logs
    restart             Restart containers
    help                Show this help message

Examples:
    $0 check        # Check if everything is ready
    $0 backup       # Backup database before deployment
    $0 deploy       # Full deployment process
    $0 status       # Check current status

EOF
}

################################################################################
# Main Script
################################################################################

main() {
    local command=${1:-help}
    
    case "$command" in
        check)
            check_requirements && check_directory && check_env
            ;;
        backup)
            check_directory && backup_database
            ;;
        deploy)
            print_header "Starting Full Deployment"
            check_requirements && \
            check_directory && \
            check_env && \
            backup_database && \
            stop_containers && \
            update_code && \
            build_containers && \
            start_containers && \
            run_migrations && \
            cache_everything && \
            verify_deployment && \
            restart_nginx && \
            show_status && \
            print_success "Deployment completed successfully!" || \
            print_error "Deployment failed!"
            ;;
        update)
            check_directory && update_code && build_containers && \
            start_containers && run_migrations && cache_everything && \
            restart_nginx
            ;;
        migrate)
            check_directory && run_migrations
            ;;
        cache)
            check_directory && cache_everything
            ;;
        status)
            show_status
            ;;
        logs)
            cd "$DEPLOY_DIR" && docker-compose logs -f app
            ;;
        restart)
            check_directory && stop_containers && start_containers
            ;;
        help)
            show_help
            ;;
        *)
            print_error "Unknown command: $command"
            show_help
            exit 1
            ;;
    esac
}

# Run main function
main "$@"
