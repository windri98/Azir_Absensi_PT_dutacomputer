import * as SQLite from 'expo-sqlite';

const db = SQLite.openDatabase('azir_absensi.db');

export const databaseService = {
  // Initialize database
  async initialize() {
    return new Promise((resolve, reject) => {
      db.transaction((tx) => {
        // Create attendances table
        tx.executeSql(
          `CREATE TABLE IF NOT EXISTS attendances (
            id INTEGER PRIMARY KEY,
            user_id INTEGER,
            date TEXT,
            check_in TEXT,
            check_out TEXT,
            status TEXT,
            check_in_location TEXT,
            check_out_location TEXT,
            notes TEXT,
            work_hours REAL,
            overtime_hours REAL,
            approval_status TEXT,
            sync_status TEXT DEFAULT 'pending',
            created_at TEXT,
            updated_at TEXT
          );`,
          [],
          () => resolve(),
          (_, error) => reject(error)
        );

        // Create sync queue table
        tx.executeSql(
          `CREATE TABLE IF NOT EXISTS sync_queue (
            id INTEGER PRIMARY KEY,
            action TEXT,
            model TEXT,
            model_id INTEGER,
            data TEXT,
            status TEXT DEFAULT 'pending',
            created_at TEXT
          );`,
          [],
          () => {},
          (_, error) => reject(error)
        );

        // Create cache table
        tx.executeSql(
          `CREATE TABLE IF NOT EXISTS cache (
            key TEXT PRIMARY KEY,
            value TEXT,
            expires_at TEXT
          );`,
          [],
          () => {},
          (_, error) => reject(error)
        );
      });
    });
  },

  // Save attendance locally
  async saveAttendance(attendance) {
    return new Promise((resolve, reject) => {
      db.transaction((tx) => {
        tx.executeSql(
          `INSERT OR REPLACE INTO attendances 
           (id, user_id, date, check_in, check_out, status, check_in_location, 
            check_out_location, notes, work_hours, overtime_hours, approval_status, 
            sync_status, created_at, updated_at)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
          [
            attendance.id,
            attendance.user_id,
            attendance.date,
            attendance.check_in,
            attendance.check_out,
            attendance.status,
            attendance.check_in_location,
            attendance.check_out_location,
            attendance.notes,
            attendance.work_hours,
            attendance.overtime_hours,
            attendance.approval_status,
            'synced',
            attendance.created_at,
            attendance.updated_at,
          ],
          () => resolve(attendance),
          (_, error) => reject(error)
        );
      });
    });
  },

  // Get all attendances
  async getAttendances() {
    return new Promise((resolve, reject) => {
      db.transaction((tx) => {
        tx.executeSql(
          'SELECT * FROM attendances ORDER BY date DESC',
          [],
          (_, result) => resolve(result.rows._array),
          (_, error) => reject(error)
        );
      });
    });
  },

  // Get today's attendance
  async getTodayAttendance() {
    return new Promise((resolve, reject) => {
      const today = new Date().toISOString().split('T')[0];
      db.transaction((tx) => {
        tx.executeSql(
          'SELECT * FROM attendances WHERE date = ? LIMIT 1',
          [today],
          (_, result) => resolve(result.rows._array[0] || null),
          (_, error) => reject(error)
        );
      });
    });
  },

  // Add to sync queue
  async addToSyncQueue(action, model, modelId, data) {
    return new Promise((resolve, reject) => {
      db.transaction((tx) => {
        tx.executeSql(
          `INSERT INTO sync_queue (action, model, model_id, data, created_at)
           VALUES (?, ?, ?, ?, ?)`,
          [action, model, modelId, JSON.stringify(data), new Date().toISOString()],
          () => resolve(),
          (_, error) => reject(error)
        );
      });
    });
  },

  // Get pending sync items
  async getPendingSyncItems() {
    return new Promise((resolve, reject) => {
      db.transaction((tx) => {
        tx.executeSql(
          "SELECT * FROM sync_queue WHERE status = 'pending' ORDER BY created_at ASC",
          [],
          (_, result) => resolve(result.rows._array),
          (_, error) => reject(error)
        );
      });
    });
  },

  // Mark sync item as synced
  async markSyncItemAsSynced(id) {
    return new Promise((resolve, reject) => {
      db.transaction((tx) => {
        tx.executeSql(
          "UPDATE sync_queue SET status = 'synced' WHERE id = ?",
          [id],
          () => resolve(),
          (_, error) => reject(error)
        );
      });
    });
  },

  // Clear old cache
  async clearOldCache() {
    return new Promise((resolve, reject) => {
      db.transaction((tx) => {
        tx.executeSql(
          "DELETE FROM cache WHERE expires_at < ?",
          [new Date().toISOString()],
          () => resolve(),
          (_, error) => reject(error)
        );
      });
    });
  },
};
