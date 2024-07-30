import React, { useState, useContext } from 'react';
import { Link } from 'react-router-dom';
import Header from './Header';
import { ToastContainer, toast } from 'react-toastify';
import { makeApiCall, makePostApiCall } from '../utils/api';
import 'react-toastify/dist/ReactToastify.css';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const { login } = useAuth();
  const navigate = useNavigate();


  const handleLogin = async () => {
    // Call backend API for login
    try {
      // Assuming you have a function makePostApiCall for making POST requests
      const postData = { email, password };
      const result = await makePostApiCall('api/login', postData);

      if (result.status === 'success') {
        login(result.token , result.user);

        // Display a success toast
        toast.success('Login successful!');

        // Redirect to the dashboard or any other protected route
        navigate('/dashboard');
      } else {
        toast.error('Login failed. Please check your credentials.');
      }
    } catch (error) {
      toast.error('An error occurred. Please try again later.');
    }
  };

  return (
    <div className="bg-gray-100 min-h-screen flex flex-col">
      <Header />
      <ToastContainer />

      <main className="container mx-auto my-8 flex-grow">
        <div className="flex flex-col items-center">
          <input
            className="py-2 px-4 mb-4 border rounded"
            type="text"
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
          <input
            className="py-2 px-4 mb-4 border rounded"
            type="password"
            placeholder="Password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
          <button
            className="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700"
            onClick={handleLogin}
          >
            Login
          </button>
          <p className="mt-4">
            Don't have an account?{' '}
            <Link to="/register" className="text-blue-500 hover:underline">
              Register here
            </Link>
          </p>
        </div>
      </main>
    </div>
  );
};

export default Login;
