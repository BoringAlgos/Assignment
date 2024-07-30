// src/components/Register.js
import React, { useState } from 'react';
import { Link   } from 'react-router-dom';
import { useNavigate } from 'react-router';
import { makeApiCall, makePostApiCall } from '../utils/api';
import Header from './Header';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const Register = () => {
  const [policyNumber, setPolicyNumber] = useState('');
  const [OTPNumber, setOTPNumber] = useState('');
  const [isOtpValidated , setOtpValidated] = useState(false)
  const [email, setEmail] = useState('');
  const [maskedEmail , setmaskedEmail] = useState('');
  const [validationError, setValidationError] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [otpMessage, setOtpMessage] = useState('');
  const [policyNumberEditable, setPolicyNumberEditable] = useState(false);
  const [registrationSuccess, setRegistrationSuccess] = useState(false);
  const navigate = useNavigate();

  const handleValidatePolicy = async () => {
    try {
      const data = await makeApiCall(`api/check-policy/${policyNumber}`);

      if (data.status === 'success') {
        setValidationError('');
        setOtpMessage(data.message);
        setPolicyNumberEditable(true);
        toast.success(data.message);
      } else {
        setValidationError(data.message);
        setOtpMessage('');
        setPolicyNumberEditable(false);
        toast.error(data.message);
      }
    } catch (error) {
      setValidationError('Please try again later');
      setOtpMessage('');
      setPolicyNumberEditable(false);
      toast.error('An error occurred. Please try again later.');
    }
  };

  const handleValidateOTP = async () => {
    const postData = {
      policy: policyNumber,
      otp: OTPNumber,
    };

    try {
      const result = await makePostApiCall('api/validate-otp', postData);

      if (result.status === 'success') {
        setOtpMessage('');
        setEmail(result.email);
        setmaskedEmail(maskEmail(result.email));
        setValidationError('');
        setOtpValidated(true);
        toast.success('OTP successfully authenticated');
      } else {
        setOtpMessage('');
        setEmail('');
        setmaskedEmail('');
        setValidationError(result.message);
        toast.error(result.message);
      }
    } catch (error) {
      setEmail('');
      setmaskedEmail('');
      setValidationError('An error occurred. Please try again later.');
      toast.error('An error occurred. Please try again later.');
    }
  };

  const handleRegister = async () => {
    if (password !== confirmPassword) {
      setValidationError('Password and Confirm Password must match');
      return;
    }

    // Call backend API for registration
    const registrationData = {
      email: email,
      password: password,
    };

    try {
      // Replace the following line with your actual registration API call
       const registrationResult = await makePostApiCall('api/register', registrationData);

      

      if (registrationResult.status === 'success') {
        toast.success('Registration successful! Redirecting to login page...');
        setRegistrationSuccess(true);

        // Redirect to the login page after 5 seconds
        setTimeout(() => {
          navigate('/login');
        }, 5000);
      } else {
        setValidationError('Registration failed. Please try again.');
      }
    } catch (error) {
      setValidationError('An error occurred. Please try again later.');
    }
  };

  // useEffect(() => {
  // //   return () => {
  // //     // Cleanup if needed
  // //   };
  // // }, [registrationSuccess]);

  const maskEmail = (email) => {
    if (!email || email.length <= 3) {
      return email; // Return original email if it's too short
    }
  
    const atIndex = email.indexOf('@');
    const maskedPart = '*'.repeat(atIndex - 3); // Mask characters between the first 3 and @
    const visiblePart = email.substring(0, 3) + maskedPart + email.substring(atIndex - 1);
  
    return visiblePart;
  };

  return (
    <div className="bg-gray-100 min-h-screen flex flex-col">
      <Header />
      <ToastContainer />

      <main className="container mx-auto my-8 flex-grow">
        {!isOtpValidated && (
        <div className="flex flex-col items-center">
          <input
            className={`py-2 px-4 mb-4 border rounded ${
              validationError ? 'border-red-500' : ''
            }`}
            type="text"
            placeholder="Policy Number"
            value={policyNumber}
            onChange={(e) => setPolicyNumber(e.target.value)}
            readOnly={policyNumberEditable}
          />
          
          {!policyNumberEditable && (
            <button
              className="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700"
              onClick={handleValidatePolicy}
            >
              Validate Policy
            </button>
          )}

          {policyNumberEditable && (
            <>
              <input
                className={`py-2 px-4 mb-4 border rounded ${
                  validationError ? 'border-red-500' : ''
                }`}
                type="text"
                value={OTPNumber}
                onChange={(e) => setOTPNumber(e.target.value)}
                placeholder="Enter OTP"
              />
              <button
                className="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700"
                onClick={handleValidateOTP}
              >
                Validate OTP
              </button>
            </>
          )}
        </div>
        )}
        {isOtpValidated && (
          <div className="flex flex-col items-center">
              <p>Email: {maskedEmail}</p>
              <input
                className={`py-2 my-2 px-4 mb-4 border rounded ${
                  validationError ? 'border-red-500' : ''
                }`}
                type="password"
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
              />
              <input
                className={`py-2 px-4 mb-4 border rounded ${
                  validationError ? 'border-red-500' : ''
                }`}
                type="password"
                placeholder="Confirm Password"
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
              />
              <button
                className="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700"
                onClick={handleRegister}
              >
                Register
              </button>
          </div>
        )}
      </main>

      <footer className="bg-gray-200 py-4">
        <div className="container mx-auto text-center text-gray-600">
          <Link to="/" className="text-blue-500 hover:underline">
            Back to Home
          </Link>
        </div>
      </footer>
    </div>
  );
};

export default Register;
