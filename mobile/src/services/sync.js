import { databaseService } from './database';
import { attendanceService } from './attendance';
import api from './api';

export const syncService = {
  async syncPendingData() {
    try {
      const pendingItems = await databaseService.getPendingSyncItems();

      for (const item of pendingItems) {
        try {
          const data = JSON.parse(item.data);

          switch (item.action) {
            case 'create':
              await this.syncCreate(item.model, data);
              break;
            case 'update':
              await this.syncUpdate(item.model, item.model_id, data);
              break;
            case 'delete':
              await this.syncDelete(item.model, item.model_id);
              break;
          }

          // Mark as synced
          await databaseService.markSyncItemAsSynced(item.id);
        } catch (error) {
          console.error(`Sync error for item ${item.id}:`, error);
          // Continue with next item
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
