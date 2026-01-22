import api, { API_BASE_URL } from './api';

const STORAGE_BASE_URL = API_BASE_URL.replace('/api/v1', '') + '/storage';

export const activityService = {
  async getPartners(search = '') {
    try {
      const response = await api.get('/partners', { params: { search } });
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async getActivities(filters = {}) {
    try {
      const response = await api.get('/activities', { params: filters });
      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async getActivityById(id) {
    try {
      const response = await api.get(`/activities/${id}`);
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async createActivity(payload) {
    try {
      const formData = new FormData();
      formData.append('partner_id', payload.partnerId);
      formData.append('title', payload.title);
      if (payload.description) {
        formData.append('description', payload.description);
      }
      formData.append('start_time', payload.startTime);
      if (payload.endTime) {
        formData.append('end_time', payload.endTime);
      }
      formData.append('signature_data', payload.signatureData);
      formData.append('signature_name', payload.signatureName);
      if (payload.latitude !== undefined && payload.latitude !== null) {
        formData.append('latitude', String(payload.latitude));
      }
      if (payload.longitude !== undefined && payload.longitude !== null) {
        formData.append('longitude', String(payload.longitude));
      }
      if (payload.locationAddress) {
        formData.append('location_address', payload.locationAddress);
      }
      formData.append('evidence', {
        uri: payload.evidence.uri,
        name: payload.evidence.name,
        type: payload.evidence.type,
      });

      const response = await api.post('/activities', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  getStorageUrl(path) {
    if (!path) return null;
    return `${STORAGE_BASE_URL}/${path}`;
  },
};
