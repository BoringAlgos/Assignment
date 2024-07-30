// LoggedInHeader.js
import React from 'react';
import { useAuth } from '../context/AuthContext';

const LoggedInHeader = () => {
  const { user, logout } = useAuth();

  return (
    <header className="flex justify-between items-center p-4 bg-blue-500 text-white">
      <div className="flex items-center">
      <h1 className="text-3xl font-bold text-white">Admin Portal</h1>
      </div>
      <div>
        <p className="text-sm">Hi, {user?.name || user?.email}</p>
        <button className="text-sm underline cursor-pointer" onClick={logout}>
          Logout
        </button>
      </div>
    </header>
  );
};

export default LoggedInHeader;
