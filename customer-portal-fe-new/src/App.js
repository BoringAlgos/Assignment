// src/App.js
import React from 'react';
import { BrowserRouter as Router, Route, Routes,Navigate } from 'react-router-dom';
import Landing from './components/pages/Landing';
import Login from './components/Login';
import Register from './components/Register';
import { useAuth } from './context/AuthContext';
import Dashboard from './components/pages/Dashboard';
import PrivateRoute from './components/PrivateRoute';
import Profile from './components/pages/Profile';
import ClaimSubmit from './components/pages/ClaimSubmit';
const App = () => {
  // You can implement your authentication logic here
  const { token } = useAuth();
  return (
    <React.Fragment>
      <PrivateRoute exact path="/" element={<Dashboard />} /> 
      <Router>
        <Routes>
          <Route path="/login" element={token ? <Navigate to="/dashboard" replace="true" /> : <Login />} />
          <Route path="/register" element={token ? <Navigate to="/dashboard" replace="true" /> : <Register />} />
          <Route path="/" element={token ? <Navigate to="/dashboard" replace="true" /> : <Navigate to="/landing" replace="true" />} />
          <Route path="/dashboard" element={token ? <Dashboard /> : <Navigate to="/landing" replace="true" />} />
          <Route path="/profile/view" element={token ? <Profile /> : <Navigate to="/landing" replace="true" />} />
          <Route path="/landing" element={token ? <Navigate to="/dashboard" replace="true" /> : <Landing />} />
          <Route path="claims/submit" element={token ? <ClaimSubmit /> : <Navigate to="/landing" replace="true" />} />
        </Routes>
      </Router>
    </React.Fragment>
  );
};

export default App;
