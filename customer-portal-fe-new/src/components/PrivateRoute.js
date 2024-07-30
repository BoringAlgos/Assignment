import React from 'react';
import { Route,Routes, Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const PrivateRoute = ({ element, ...rest }) => {
  const { token } = useAuth();

  <Routes>
    <Route
      {...rest}
      element={token ? element : <Navigate to="/landing" replace={true} />}
    />
    </Routes>
};

export default PrivateRoute;
