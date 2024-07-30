import React, { useState, useEffect } from 'react';
import { makeApiCall, makeApiCallEclaim,makeApiCallCustomer } from '../utils/api';
import { useAuth } from '../context/AuthContext';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import ClaimDetails from './ClaimDetails';
const ClaimListContent = () => {
  const [claims, setClaims] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedClaim, setSelectedClaim] = useState(null);
  const { token, user } = useAuth();
  const [isLoading, setIsLoading] = useState(true);

  const viewDetails = async (claimId) => {
    const claim = claims.find((c) => c.id === claimId);
    
    try {
      setIsLoading(true);
      const detailsResponse = await makeApiCall('api/get-details?customer_id='+claim.customer_id+'&policy_id='+claim.policy_id
      , {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
  
      // Assuming the API response contains customer, policy, and vehicle information
      const { customerInfo, policyInfo, vehicleInfo } = detailsResponse;
  
      // Combine the fetched details with the claim information
      const detailsWithClaim = {
        ...claim,
        customerInfo,
        policyInfo,
        vehicleInfo,
      };
  
      // Update the state with the selected claim and details
      setSelectedClaim(detailsWithClaim);
    } catch (error) {
      console.error('Error fetching details:', error.message);
      // Handle error as needed
    }finally {
      setIsLoading(false); // End loading
    }
  };
  

  useEffect(() => {
    const fetchClaims = async () => {
      
      try {
        const postData = new FormData();
        postData.append('role', user.roles[0]?.name);
        postData.append('user', user.id);
        const claimsResponse = await makeApiCall('api/claim-list', {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
          },
          body: postData
        });

        // Set the claims in the state
        const formattedClaims = claimsResponse.claims.map((claim) => ({
          ...claim,
          created_at: new Date(claim.created_at).toLocaleString(), // Adjust the format as needed
        }));
        setClaims(formattedClaims);
      } catch (error) {
        console.error('Error fetching users:', error);
      } finally {
        setLoading(false);
      }
    };

    // Call the fetchClaims function
    fetchClaims();
  }, []);

  if (loading) {
    return <p>Loading...</p>;
  }

  return (
    <div className="ml-2 mt-1">
      <h2 className="text-2xl font-bold mb-4">Claims List</h2>
      <div className="flex">
        {/* Left side with claims table */}
      <div className="shadow-md mx-auto h-full overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Claim Id
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created At
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {claims.map((claim) => (
              <tr key={claim.id}>
                <td className="px-3 py-3 whitespace-nowrap">{claim.id}</td>
                <td className="px-3 py-3 whitespace-nowrap">{claim.claim_status}</td>
                <td className="px-3 py-3 whitespace-nowrap">{claim.created_at}</td>
                <td className="px-3 py-3 whitespace-nowrap">
                  <button
                    onClick={() => viewDetails(claim.id)}
                    className="px-1 py-1 bg-green-500 hover:bg-green-600 text-white rounded transition duration-300 ease-in-out"
                  >
                    View Claim
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      {/* Right side with claim details */}
      {selectedClaim && (
          <div className="flex-1 shadow-md border-gray-300 p-4 pl-4 ml-4">
            <ClaimDetails claim={selectedClaim} />
          </div>
        )}
      </div>
    </div>
  );
};

export default ClaimListContent;
