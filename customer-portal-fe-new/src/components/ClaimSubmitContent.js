import React, { useState, useEffect } from 'react';
import { makeApiCall , makeApiCallEclaim } from '../utils/api';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useAuth } from '../context/AuthContext';

const ClaimSubmitContent = () => {
  const [policyNumber, setPolicyNumber] = useState('');
  const [vehicleId, setVehicleId] = useState('');
  const [incidentDetails, setIncidentDetails] = useState('');
  const [locationAreaCode, setlocationAreaCode] = useState('');
  const [documentType, setDocumentType] = useState('');
  const [selectedFile, setSelectedFile] = useState(null);
  const [loading, setLoading] = useState(false);
  const [policyList, setPolicyList] = useState([]);
  const [vehicleDetailsList, setVehicleDetailsList] = useState([]);
  const { token , user } = useAuth();
  const { id } = user;
  console.log(id);
  useEffect(() => {
    const fetchPolicyDetails = async () => {
      try {
        const apiEndpoint = 'api/user/policy-details';
        const headers = {
          Authorization: `Bearer ${token}`,
        };
        const data = await makeApiCall(apiEndpoint, { headers });

        setPolicyList(data.policyList);
      } catch (error) {
        console.error('Error fetching policy details:', error);
      }
    };

    fetchPolicyDetails();
  }, [token]);

  const handlePolicyChange = async (selectedPolicy) => {
    setPolicyNumber(selectedPolicy);

    if (selectedPolicy !== 'Select Policy') {
      try {
        const apiEndpoint = `api/user/vehicle-details?policyId=${selectedPolicy}`;
        const headers = {
          Authorization: `Bearer ${token}`,
        };
        const data = await makeApiCall(apiEndpoint, { headers });

        setVehicleDetailsList(data.vehicleDetailsList);
      } catch (error) {
        console.error('Error fetching vehicle details:', error);
      }
    } else {
      setVehicleDetailsList([]); // Reset vehicle details list if "Select Policy" is chosen
      setVehicleId(''); // Reset vehicleId state
    }
  };

  const handleVehicleChange = (selectedVehicle) => {
    setVehicleId(selectedVehicle);
  };

  const fetchEClaimToken = async () => {
    try {
      const apiEndpoint = 'api/generate-eclaim-token';
      const headers = {
        Authorization: `Bearer ${token}`, // Use a secure method to authenticate this request
      };
      const postData = new FormData();
      postData.append('client_id','eclaim-system');
      const response = await makeApiCall(apiEndpoint, { method: 'POST', headers, body: postData });
      return response.token;
    } catch (error) {
      console.error('Error generating e-claim token:', error);
      throw error;
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Construct form data to send to the API
    const formData = new FormData();
    formData.append('customer_id', id);
    formData.append('policy_id', policyNumber);
    formData.append('location_area_code',locationAreaCode);
    formData.append('vehicle_id', vehicleId);
    formData.append('incident_details', incidentDetails);
    formData.append('document_type', documentType);
    formData.append('document', selectedFile);

    try {
      setLoading(true);
      const eClaimToken = await fetchEClaimToken();
      const apiEndpoint = 'api/claims'; // Replace with your actual endpoint
      const headers = {
        Authorization: `Bearer ${eClaimToken}`,
      };
      const data = await makeApiCallEclaim(apiEndpoint, { method: 'POST', headers, body: formData });
      toast.success('Claim submitted successfully!');
      console.log('Claim submitted successfully:', data);
      // Optionally, you can redirect the user or show a success message
    } catch (error) {
      console.error('Error submitting claim:', error);
      toast.error('Error submitting claim!')
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="flex-1 p-4 ml-8">
      <h2 className="text-2xl font-bold mb-4">Claim Submission</h2>
      <ToastContainer />
      <form enctype="multipart/form-data" onSubmit={handleSubmit}>
        {/* Policy Details Section */}
        <div className="mb-4">
          <label htmlFor="policyNumber" className="block text-sm font-medium text-gray-700">
            Policy Number
          </label>
          <select
            id="policyNumber"
            name="policyNumber"
            value={policyNumber}
            onChange={(e) => handlePolicyChange(e.target.value)}
            className="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300"
          >
            <option value="" disabled>Select Policy</option>
            {policyList.map((policy) => (
              <option key={policy.id} value={policy.id}>
                {policy.policy_number}
              </option>
            ))}
          </select>
        </div>
        <div className="mb-4">
          <label htmlFor="vehicleDetails" className="block text-sm font-medium text-gray-700">
            Vehicle Details
          </label>
          <select
            id="vehicleDetails"
            name="vehicleDetails"
            value={vehicleId}
            onChange={(e) => handleVehicleChange(e.target.value)}
            className="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300"
          >
            <option value="" disabled>Select Vehicle</option>
            {vehicleDetailsList.map((vehicle) => (
              <option key={vehicle.id} value={vehicle.id}>
                {vehicle.make} {vehicle.model} - {vehicle.year}
              </option>
            ))}
          </select>
        </div>
        {/* Incident Details Section */}
        <div className="mb-4">
          <h3 className="text-xl font-semibold mb-2">Incident Area Code</h3>
          <input type text
            className="w-full p-2 border rounded"
            placeholder="Enter incident area code"
            value={locationAreaCode}
            onChange={(e) => setlocationAreaCode(e.target.value)}
            required
          />
        </div>
        <div className="mb-4">
          <h3 className="text-xl font-semibold mb-2">Incident Details</h3>
          <textarea
            className="w-full p-2 border rounded"
            placeholder="Enter incident details..."
            value={incidentDetails}
            onChange={(e) => setIncidentDetails(e.target.value)}
            required
          />
        </div>

        {/* Document Submission Section */}
        <div className="mb-4">
          <h3 className="text-xl font-semibold mb-2">Document Submission</h3>
          <select
            className="w-full p-2 mb-2 border rounded"
            value={documentType}
            onChange={(e) => setDocumentType(e.target.value)}
            required
          >
            <option value="">Select document type</option>
            <option value="Accident Photos">Accident Photos</option>
            <option value="FIR Copy">FIR Copy</option>
            {/* Add document type options here */}
          </select>
          <input
            type="file"
            accept="image/*, application/pdf"
            onChange={(e) => setSelectedFile(e.target.files[0])}
            required
          />
        </div>

        {/* Submit Button */}
        <button
          type="submit"
          className="bg-blue-500 text-white px-4 py-2 rounded"
          disabled={loading}
        >
          {loading ? 'Submitting...' : 'Submit Claim'}
        </button>
      </form>
    </div>
  );
};

export default ClaimSubmitContent;
