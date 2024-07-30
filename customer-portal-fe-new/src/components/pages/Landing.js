// src/components/LandingPage.js
import React from 'react';
import { Link } from 'react-router-dom';
import Header from '../Header';

const LandingPage = () => {
  return (
    <div className="bg-gray-100 min-h-screen flex flex-col">
      <Header />

      <main className="container mx-auto my-8 flex-grow">
        <div className="text-center">
          <h2 className="text-2xl font-semibold mb-4">Welcome to YCompany E-Claims Portal</h2>
          {/* <p className="text-gray-600">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. ...
          </p> */}
        </div>

        <div className="mt-8 flex justify-center">
          <Link to="/login" className="bg-blue-500 text-white py-2 px-4 mr-4 rounded hover:bg-blue-700">
            Login
          </Link>
          <Link to="/register" className="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-700">
            Register
          </Link>
        </div>
      </main>

      <footer className="bg-gray-200 py-4">
        <div className="container mx-auto text-center text-gray-600">
          &copy; 2023 YCompany E-Claims. All rights reserved.
        </div>
      </footer>
    </div>
  );
};

export default LandingPage;
