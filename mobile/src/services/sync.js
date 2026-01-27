import { databaseService } from './database';
import { attendanceService } from './attendance';
import api from './api';

// Retry with exponential backoff
const retryWithBackoff = async (fn, maxRetries = 3) => {
  let lastError;
  for (let i = 0; i < maxRetries; i++) {
    try {
      return await fn();
    } catch (error) {
      lastError = error;
      // Exponential backoff: 1s, 2s, 4s
      const delay = Math.pow(2, i) * 1000;
      console.warn(`Retry attempt ${i + 1}/${maxRetries} after ${delay}ms`);
      await new Promise(resolve => setTimeout(resolve, delay));
    }
  }
  throw lastError;
};

export const syncService = {
  async syncPendingData() {
    try {
      const pendingItems = await databaseService.getPendingSyncItems();

      for (const item of pendingItems) {
        try {
          const data = JSON.parse(item.data);

          // Use retry logic for sync operations
          await retryWithBackoff(async () => {
            switch (item.action) {
              case 'create':
                return await this.syncCreate(item.model, data);
              case 'update':
                return await this.syncUpdate(item.model, item.model_id, data);
              case 'delete':
                return await this.syncDelete(item.model, item.model_id);
            }
          }, 3);

          // Mark as synced
          await databaseService.markSyncItemAsSynced(item.id);
        } catch (error) {
          console.error(`Sync error for item ${item.id}:`, error);
          // Continue with next item even if sync fails
        }
      }

      return true;
    } catch (error) {
      console.error('Sync pending data error:', error);
      throw error;
    }
  },

  async syncCreate(model, data) {
    switch (model) {
      case 'Attendance':
        return await attendanceService.checkIn(data.location);
      default:
        throw new Error(`Unknown model: ${model}`);
    }
  },

  async syncUpdate(model, modelId, data) {
    switch (model) {
      case 'Attendance':
        return await api.put(`/attendances/${modelId}`, data);
      default:
        throw new Error(`Unknown model: ${model}`);
    }
  },

  async syncDelete(model, modelId) {
    switch (model) {
      case 'Attendance':
        return await api.delete(`/attendances/${modelId}`);
      default:
        throw new Error(`Unknown model: ${model}`);
    }
  },

  async queueOfflineAction(action, model, modelId, data) {
    try {
      await databaseService.addToSyncQueue(action, model, modelId, data);
      return true;
    } catch (error) {
      console.error('Queue offline action error:', error);
      throw error;
    }
  },

  async syncAttendanceData() {
    try {
      // Get all local attendances
      const localAttendances = await databaseService.getAttendances();

      // Sync with server
      for (const attendance of localAttendances) {
        if (attendance.sync_status === 'pending') {
          try {
            // Try to sync with server
            await attendanceService.getAttendanceById(attendance.id);
            // If successful, mark as synced
            await databaseService.saveAttendance({
              ...attendance,
              sync_status: 'synced',
            });
          } catch (error) {
            console.error(`Sync attendance ${attendance.id} error:`, error);
          }
        }
      }

      return true;
    } catch (error) {
      console.error('Sync attendance data error:', error);
      throw error;
    }
  },
};
