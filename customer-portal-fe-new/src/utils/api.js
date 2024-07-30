// src/utils/api.js
export const API_URL = 'http://www.ycountry-eclaim.local:8000';
export const ECLAIM_URL = 'http://www.ycountry-eclaim.core.local:8002';

export const makeApiCall = async (endpoint, options = {}) => {
  try {
    const response = await fetch(`${API_URL}/${endpoint}`, options);

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message);
    }

    return response.json();
  } catch (error) {
    console.error('API Error:', error.message);
    throw error;
  }
};


export const makeApiCallEclaim = async (endpoint, options = {}) => {
  try {
    const response = await fetch(`${ECLAIM_URL}/${endpoint}`, options);

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message);
    }

    return response.json();
  } catch (error) {
    console.error('API Error:', error.message);
    throw error;
  }
};

export const makePostApiCall = async (endpoint, data) => {
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        // Add any other headers as needed
      },
      body: JSON.stringify(data),
    };
  
    return makeApiCall(endpoint, options);
  };
