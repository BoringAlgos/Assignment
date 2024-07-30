// Dashboard.js
import React from 'react';
import LoggedInHeader from '../LoggedInHeader';
import LeftSidebar from '../LeftSidebar';
import DashboardContent from '../DashboardContent';

const Dashboard = () => {
  return (
    <div className="container">
      <LoggedInHeader />
      <main className="flex">
        <LeftSidebar />
        <DashboardContent />
      </main>
    </div>
  );
};

export default Dashboard;
