// src/App.js
import React from 'react';
import { BrowserRouter as Router, Route, Routes,Navigate } from 'react-router-dom';
import Login from './components/Login';
import Register from './components/Register';
import { useAuth } from './context/AuthContext';
import Dashboard from './components/pages/Dashboard';
import PrivateRoute from './components/PrivateRoute';
import UserList from './components/pages/UserList';
import ClaimList from './components/pages/ClaimList';
const App = () => {
  // You can implement your authentication logic here
  const { token } = useAuth();
  return (
    <React.Fragment>
      <PrivateRoute exact path="/" element={<Dashboard />} /> 
      <Router>
        <Routes>
          <Route path="/login" element={token ? <Navigate to="/dashboard" replace="true" /> : <Login />} />
          <Route path="/" element={token ? <Navigate to="/dashboard" replace="true" /> : <Login />} />
          <Route path="/dashboard" element={token ? <Dashboard /> : <Navigate to="/login" replace="true" />} />
          <Route path="/users/list" element={token ? <UserList /> : <Navigate to="/login" replace="true" />} />
          <Route path="/claims/list" element={token ? <ClaimList /> : <Navigate to="/login" replace="true" />} />
        </Routes>
      </Router>
    </React.Fragment>
  );
};

export default App;
