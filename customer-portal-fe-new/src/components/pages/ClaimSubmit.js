// Dashboard.js
import React from 'react';
import LoggedInHeader from '../LoggedInHeader';
import LeftSidebar from '../LeftSidebar';
import ClaimSubmitContent from '../ClaimSubmitContent';

const ClaimSubmit = () => {
  return (
    <div className="container">
      <LoggedInHeader />
      <main className="flex">
        <LeftSidebar />
        <ClaimSubmitContent />
      </main>
    </div>
  );
};

export default ClaimSubmit;
