import React from 'react';
import { Link, Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const LeftSidebarList = () => {
  const { user } = useAuth();
  const { permissions } = user;

  const allowedUserPermissions = ['users-view', 'users-add'];
  const allowedWorkflowPermissions = ['workflow-view', 'workflow-add'];

  const showUserSection = allowedUserPermissions.some(permission => permissions.some(p => p.name === permission));
  const showWorkflowSection = allowedWorkflowPermissions.some(permission => permissions.some(p => p.name === permission));

  const redirectToDashboard = <Navigate to="/dashboard" replace="true" />;

  const handleLinkClick = (event) => {
    event.preventDefault();
    toast.info('This feature will be available soon!', {
      position: toast.POSITION.TOP_CENTER,
      autoClose: 3000,
    });
  };

  return (
    <aside className="h-full bg-gray-800 text-white  w-1/3 md:w-1/4 lg:w-1/5 xl:w-1/6">
      <ToastContainer />
      <nav className="p-4">
        <ul>
          {showUserSection && (
            <li>
              <span className="block py-2 px-4 font-semibold">Users</span>
              <ul className="pl-4">
                <li>
                  <Link to="/users/list" className="block py-2 px-4">User List</Link>
                </li>
                <li>
                  <Link to="/users/add" onClick={handleLinkClick} className="block py-2 px-4">Add User</Link>
                </li>
              </ul>
            </li>
          )}
          {showWorkflowSection && (
            <li>
              <span className="block py-2 px-4 font-semibold">Workflow</span>
              <ul className="pl-4">
                <li>
                  <Link to="/workflow/list" onClick={handleLinkClick} className="block py-2 px-4">Workflow List</Link>
                </li>
                <li>
                  <Link to="/workflow/add" onClick={handleLinkClick} className="block py-2 px-4">Add Workflow</Link>
                </li>
              </ul>
            </li>
          )}
          {permissions.some(p => p.name === 'claim-view') && (
            <li>
              <Link to="/claims/list" className="block py-2 px-4">Claims</Link>
            </li>
          )}
          {!permissions.some(p => p.name === 'claim-view') && redirectToDashboard()}
        </ul>
      </nav>
    </aside>
  );
};

export default LeftSidebarList;
