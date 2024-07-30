// ProfileContent.js
import React, { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { ToastContainer, toast } from 'react-toastify';
import { makeApiCall } from '../utils/api';
import 'react-toastify/dist/ReactToastify.css';

const ProfileContent = () => {
  const { token , user } = useAuth();
  const { email } = user;
  const [name, setName] = useState('');
  const [billingCycle, setBillingCycle] = useState('');
  const [address, setAddress] = useState('');

  useEffect(() => {
    // Fetch policy details on component mount
    fetchPolicyDetails();
  }, []);

  const fetchPolicyDetails = async () => {
    try {
      // Assuming you have an API endpoint for fetching policy details
      const apiEndpoint = 'api/get-details/'+email;
      const headers = {
        Authorization: `Bearer ${token}`,
      };

      const response = await makeApiCall(apiEndpoint, { headers });
      const { data } = response;

      // Update state with fetched data
      setName(data.name || '');
      setBillingCycle(data.billing_cycle || '');
      setAddress(data.address || '');
    } catch (error) {
      // Handle error
      console.error('Error fetching policy details:', error.message);
    }
  };

  const handleSubmit = async () => {
    
    // Example: Update policy details
    try {
      const apiEndpoint = 'api/update-details';
      const headers = {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      };

      const body = JSON.stringify({
        name,
        billing_cycle: billingCycle,
        address,
        email,
      });

      const response = await makeApiCall(apiEndpoint, {
        method: 'PUT',
        headers,
        body,
      });

      toast.success('Profile Data Updated Successfully!');
    } catch (error) {
      // Handle error
      toast.error('Please Try Again Later!');
    }
  };

  return (
    <section className="flex-grow p-4">
        <ToastContainer />
      <h2 className="text-2xl font-bold mb-4">Profile Information</h2>
      <form>
        <div className="mb-4">
          <label htmlFor="name" className="block text-sm font-medium text-gray-700">
            Name
          </label>
          <input
            type="text"
            id="name"
            name="name"
            value={name}
            onChange={(e) => setName(e.target.value)}
            className="mt-1 p-2 border rounded-md w-full"
          />
        </div>
        <div className="mb-4">
          <label htmlFor="billingCycle" className="block text-sm font-medium text-gray-700">
            Billing Cycle
          </label>
          <select
            id="billingCycle"
            name="billingCycle"
            value={billingCycle}
            onChange={(e) => setBillingCycle(e.target.value)}
            className="mt-1 p-2 border rounded-md w-full"
          >
            <option value="annual">
              Annual
            </option>
            <option value="monthly">
              Monthly
            </option>
            <option value="semester">
              Semester
            </option>
            <option value="quarterly">
              Quarterly
            </option>
          </select>
        </div>
        <div className="mb-4">
          <label htmlFor="address" className="block text-sm font-medium text-gray-700">
            Address
          </label>
          <textarea
            id="address"
            name="address"
            value={address}
            onChange={(e) => setAddress(e.target.value)}
            className="mt-1 p-2 border rounded-md w-full"
          />
        </div>
        <button
          type="button"
          className="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700"
          onClick={handleSubmit}
        >
          Submit
        </button>
      </form>
    </section>
  );
};

export default ProfileContent;
