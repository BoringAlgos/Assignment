import React,{useState} from 'react';
import ReviewPopup from './ReviewPopup';
import { useAuth } from '../context/AuthContext';
import AdminReviewPopup from './AdminReviewPopup';
const ClaimDetails = ({ claim }) => {
  const [showPopup, setShowPopup] = useState(false);
  const {token , user } = useAuth();
  const userRole = user.roles.map(role => role.name);
  const handleReviewClick = () => {
    setShowPopup(true);
  };

  const handleClosePopup = () => {
    setShowPopup(false);
  };
  const renderPopup = () => {
    if (userRole.includes("Adjustor") || userRole.includes("Claim Manager") || userRole.includes("Admin")) {
      return <AdminReviewPopup claimId={claim.id} onClose={handleClosePopup} userRole={userRole} />;
    }
    return <ReviewPopup claimId={claim.id} onClose={handleClosePopup} />;
  };
  const renderButton = () => {
    if (userRole.includes("Admin")) {
      return <button className='px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition duration-300 ease-in-out' onClick={handleReviewClick}>Assign</button>;
    }
    if (userRole.includes("Claim Manager")) {
      return (
        <>
          <button className='px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition duration-300 ease-in-out' onClick={handleReviewClick}>Review</button>
          <button className='px-4 py-2 ml-4 bg-green-500 hover:bg-green-600 text-white rounded transition duration-300 ease-in-out' onClick={handleReviewClick}>Assign</button>
        </>
      );
    }
    return <button className='px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition duration-300 ease-in-out' onClick={handleReviewClick}>Review</button>;
    
  };

  return (
    <div className="flex">
      {/* Left Side: Claim Details */}
      <div className="flex-1">
        <h2 className="text-2xl font-bold mb-4">Claim Details</h2>

        <div className="grid grid-cols-2 gap-8">
          {/* Section 1: Customer Details */}
          <div>
            <div className="mb-6">
              <h3 className="text-lg font-semibold mb-2">Customer Details</h3>
              <p className="mb-2">
                <span className="font-semibold">Customer ID:</span> {claim.customer_id}
              </p>
              <p className="mb-2">
                <span className="font-semibold">Email:</span> {claim.customerInfo.email}
              </p>
              <p className="mb-2">
                <span className="font-semibold">Name:</span> {claim.customerInfo.name}
              </p>
              {/* Add other customer details as needed */}
            </div>
          </div>

          {/* Section 2: Policy, Incident Details, and Incident Image */}
          <div>
            <div className="mb-6">
              <h3 className="text-lg font-semibold mb-2">Policy Details</h3>
              <p className="mb-2">
                <span className="font-semibold">Policy ID:</span> {claim.policy_id}
              </p>
              <p className="mb-2">
                <span className="font-semibold">Policy No:</span> {claim.policyInfo.policy_number}
              </p>
              <p className="mb-2">
                <span className="font-semibold">Policy Expiry:</span> {claim.policyInfo.expiry}
              </p>
              {/* Add other policy details as needed */}
            </div>
          </div>
          <div>
            <div className="mb-6">
              <h3 className="text-lg font-semibold mb-2">Incident Details</h3>
              <p className="mb-2">
                <span className="font-semibold">Incident ID:</span> {claim.incident.id}
              </p>
              <p className="mb-2">
                <span className="font-semibold"> Area Code:</span> {claim.incident.location_area_code}
              </p>
              
              <p className="mb-2">
                <span className="font-semibold"> Registration No:</span> {claim.vehicleInfo.registration_id}
              </p>
              <p className="mb-2">
                <span className="font-semibold">Make:</span> {claim.vehicleInfo.make}
              </p>
              <p className="mb-2">
                <span className="font-semibold">Model:</span> {claim.vehicleInfo.model}
              </p>
              <p className="mb-2">
                <span className="font-semibold">Color:</span> {claim.vehicleInfo.color}
              </p>
              <p className="mb-2">
                <span className="font-semibold">Incident Description:</span> {claim.incident.incident_description}
              </p>
            </div>
          </div>
          <div>
            <div className="mb-6">
              <h3 className="text-lg font-semibold mb-2">Incident Image</h3>
              {claim.claim_documents.map((document) => (
                <div key={document.id} className="mb-4">
                  <p className="mb-2">
                    <span className="font-semibold">Document Type:</span> {document.document_type}
                  </p>
                  <img
                    src={document.link}
                    alt={`Incident ${document.document_type}`}
                    className="max-w-full"
                  />
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Right Side: Review Button */}
      <div className="ml-auto">
        <div className="mt-0">
        {/* Render button based on role */}
        {renderButton()}
        </div>
      </div>
      {showPopup && renderPopup()}
    </div>
  );
};

export default ClaimDetails;
