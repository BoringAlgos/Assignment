import React from 'react';
import LoggedInHeader from '../LoggedInHeader';
import LeftSidebarList from '../LeftSidebarList';
import ClaimListContent from '../ClaimListContent';

const ClaimList = () => {
  return (
    <div className="flex flex-col h-screen">
      <LoggedInHeader />
      <main className="flex-grow flex">
        <LeftSidebarList className="flex-shrink-0" />
        <ClaimListContent />
      </main>
    </div>
  );
};

export default ClaimList;
