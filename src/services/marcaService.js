import axios from 'axios';
import { API_BASE_URL } from '../config/api';

const MARCA_ENDPOINT = `${API_BASE_URL}/marca`;

// Configurar axios con timeout
const axiosInstance = axios.create({
  timeout: 10000, // 10 segundos
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export const marcaService = {
  // Obtener todas las marcas
  async getMarcas() {
    try {
      const response = await axiosInstance.get(MARCA_ENDPOINT);
      console.log('Respuesta completa de la API:', response.data);
      
      if (response.data.success) {
        // Laravel devuelve paginación: response.data.data.data
        const marcas = response.data.data.data || [];
        console.log('Marcas procesadas:', marcas); // Debug IDs
        return { success: true, data: marcas };
      } else {
        return { success: false, error: 'Error al obtener marcas' };
      }
    } catch (error) {
      console.error('Error en getMarcas:', error);
      return { 
        success: false, 
        error: error.response?.data?.message || error.message || 'Error de conexión' 
      };
    }
  },

  // Crear una nueva marca
  async createMarca(nombreMarca) {
    try {
      const response = await axiosInstance.post(MARCA_ENDPOINT, { 
        nombre_marca: nombreMarca 
      });
      
      if (response.data.success) {
        return { success: true, data: response.data.data };
      } else {
        return { success: false, error: response.data.message || 'Error al crear marca' };
      }
    } catch (error) {
      console.error('Error en createMarca:', error);
      return { 
        success: false, 
        error: error.response?.data?.message || error.message || 'Error de conexión' 
      };
    }
  },

  // Actualizar una marca
  async updateMarca(id, nombreMarca) {
    try {
      const response = await axiosInstance.put(`${MARCA_ENDPOINT}/${id}`, { 
        nombre_marca: nombreMarca 
      });
      
      if (response.data.success) {
        return { success: true, data: response.data.data };
      } else {
        return { success: false, error: response.data.message || 'Error al actualizar marca' };
      }
    } catch (error) {
      console.error('Error en updateMarca:', error);
      return { 
        success: false, 
        error: error.response?.data?.message || error.message || 'Error de conexión' 
      };
    }
  },

  // Eliminar una marca
  async deleteMarca(id) {
    try {
      const response = await axiosInstance.delete(`${MARCA_ENDPOINT}/${id}`);
      
      if (response.data.success) {
        return { success: true };
      } else {
        return { success: false, error: response.data.message || 'Error al eliminar marca' };
      }
    } catch (error) {
      console.error('Error en deleteMarca:', error);
      return { 
        success: false, 
        error: error.response?.data?.message || error.message || 'Error de conexión' 
      };
    }
  },
};
