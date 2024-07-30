// Dashboard.js
import React from 'react';
import LoggedInHeader from '../LoggedInHeader';
import LeftSidebar from '../LeftSidebar';
import ProfileContent from '../ProfileContent';

const Profile = () => {
  return (
    <div className="container">
      <LoggedInHeader />
      <main className="flex">
        <LeftSidebar />
        <ProfileContent />
      </main>
    </div>
  );
};

export default Profile;
