// ReviewPopup.js
import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { makeApiCall } from '../utils/api';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
const ReviewPopup = ({ claimId, onClose }) => {
  const [formData, setFormData] = useState([
    { part: '', description: '', damageType: '', proposedAmount: '' },
  ]);
  
  const { token, user } = useAuth();
  const handleInputChange = (index, field, value) => {
    const updatedFormData = [...formData];
    updatedFormData[index][field] = value;
    setFormData(updatedFormData);
  };

  const handleAddNew = () => {
    if (formData.length < 15) {
      setFormData([...formData, { part: '', description: '', damageType: '', proposedAmount: '' }]);
    }
  };

  const handleRemove = (index) => {
    const updatedFormData = [...formData];
    updatedFormData.splice(index, 1);
    setFormData(updatedFormData);
  };

  const handleSubmit = async(e) => {
    try {
      e.preventDefault();
      const postData = new FormData();
        postData.append('claimId', claimId);
        postData.append('items', JSON.stringify(formData));
      const claimsResponse = await makeApiCall('api/claim-work', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
        },
        body: postData
      });
      toast.success('Review For Claim has been submitted successfully!', {
        position: toast.POSITION.TOP_CENTER,
        autoClose: 3000,
      });
      onClose();
    } catch (error) {
      toast.error('Something went wrong. Please try again later!', {
        position: toast.POSITION.TOP_CENTER,
        autoClose: 3000,
      });
      onClose();
    }
  
  };

  return (
    <div className="fixed inset-0 z-50 overflow-auto bg-gray-800 bg-opacity-50 flex justify-center items-center">
      <div className="bg-white p-6 rounded-lg max-w-3xl w-full h-3/4 overflow-auto">
        <button
          className="self-end text-gray-600 hover:text-red-500 cursor-pointer"
          onClick={onClose}
        >
          Close
        </button>
        <h2 className="text-2xl font-bold mb-4">Review Claim</h2>
        <form onSubmit={handleSubmit}>
          <input type="hidden" name="claimId" value={claimId} />

          {formData.map((item, index) => (
            <div key={index} className="flex space-x-4 mb-4">
              <input
                type="text"
                placeholder="Part"
                value={item.part}
                onChange={(e) => handleInputChange(index, 'part', e.target.value)}
                className="p-2 border rounded w-1/4" // Adjust the width as needed
              />
              <input
                type="text"
                placeholder="Description"
                value={item.description}
                onChange={(e) => handleInputChange(index, 'description', e.target.value)}
                className="p-2 border rounded w-1/4" // Adjust the width as needed
              />
              <select
                value={item.damageType}
                onChange={(e) => handleInputChange(index, 'damageType', e.target.value)}
                className="p-2 border rounded w-1/4" // Adjust the width as needed
              >
                <option value="" disabled>
                  Select Damage Type
                </option>
                <option value="small">Small</option>
                <option value="severe">Severe</option>
                {/* Add other damage type options as needed */}
              </select>
              <input
                type="number"
                placeholder="Proposed Amount"
                value={item.proposedAmount}
                onChange={(e) => handleInputChange(index, 'proposedAmount', e.target.value)}
                className="p-2 border rounded w-1/4" // Adjust the width as needed
              />
              {index > 0 && (
                <button
                  type="button"
                  onClick={() => handleRemove(index)}
                  className="bg-red-500 text-white rounded p-2"
                >
                  Remove
                </button>
              )}
            </div>
          ))}

          {formData.length < 15 && (
            <button
              type="button"
              onClick={handleAddNew}
              className="bg-green-500 text-white rounded p-2"
            >
              Add New
            </button>
          )}

          <div className="flex justify-end mt-4">
            <button type="submit" className="bg-blue-500 text-white rounded p-2">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ReviewPopup;
