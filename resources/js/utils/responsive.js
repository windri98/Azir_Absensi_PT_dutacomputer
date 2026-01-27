/**
 * Responsive utilities for consistent responsive behavior across components
 */

/**
 * Responsive padding classes
 * Mobile first: base value, then override with sm:, md:, lg: prefixes
 */
export const RESPONSIVE_PADDING = {
  container: 'p-4 sm:p-6 md:p-8',
  card: 'p-4 sm:p-6',
  section: 'p-3 sm:p-4 md:p-6',
  small: 'p-2 sm:p-3',
};

/**
 * Responsive gap classes for grids and flex
 */
export const RESPONSIVE_GAP = {
  small: 'gap-2 sm:gap-3',
  medium: 'gap-3 sm:gap-4 md:gap-6',
  large: 'gap-4 sm:gap-6 md:gap-8',
};

/**
 * Responsive typography classes
 */
export const RESPONSIVE_TEXT = {
  h1: 'text-2xl sm:text-3xl md:text-4xl font-bold',
  h2: 'text-xl sm:text-2xl md:text-3xl font-bold',
  h3: 'text-lg sm:text-xl md:text-2xl font-semibold',
  h4: 'text-base sm:text-lg font-semibold',
  body: 'text-sm sm:text-base',
  small: 'text-xs sm:text-sm',
};

/**
 * Responsive grid templates
 */
export const RESPONSIVE_GRID = {
  // 1 col on mobile, 2 on tablet, 3+ on desktop
  card: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  // 1 col on mobile, 2 on tablet, 4 on desktop
  stat: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
  // 1 col on mobile, full width on tablet+
  form: 'grid-cols-1 md:grid-cols-2',
};

/**
 * Container sizing classes
 */
export const RESPONSIVE_WIDTH = {
  full: 'w-full',
  maxSmall: 'max-w-sm',
  maxMedium: 'max-w-md',
  maxLarge: 'max-w-lg',
  maxXL: 'max-w-xl',
};

export default {
  RESPONSIVE_PADDING,
  RESPONSIVE_GAP,
  RESPONSIVE_TEXT,
  RESPONSIVE_GRID,
  RESPONSIVE_WIDTH,
};
