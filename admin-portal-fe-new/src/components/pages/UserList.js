import React from 'react';
import LoggedInHeader from '../LoggedInHeader';
import LeftSidebarList from '../LeftSidebarList';
import UserListContent from '../UserListContent';

const UserList = () => {
  return (
    <div className="flex flex-col h-screen">
      <LoggedInHeader />
      <main className="flex-grow flex">
        <LeftSidebarList className="flex-shrink-0" />
        <UserListContent />
      </main>
    </div>
  );
};

export default UserList;
