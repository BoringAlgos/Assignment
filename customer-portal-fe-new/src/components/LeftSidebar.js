// LeftSidebar.js
import React from 'react';
import { Link } from 'react-router-dom';

const LeftSidebar = () => {
  return (
    <aside className="bg-gray-800 text-white h-screen w-1/4">
      <nav className="p-4">
        <ul>
          <li>
            <Link to="/profile/view" className="block py-2 px-4">Profile</Link>
          </li>
          <li>
            <Link to="/claims" className="block py-2 px-4">Claims</Link>
            <ul className="pl-4">
              <li>
                <Link to="/claims/submit" className="block py-2 px-4">Submit New Claim</Link>
              </li>
              <li>
                <Link to="/claims/status" className="block py-2 px-4">View Claims Status</Link>
              </li>
            </ul>
          </li>
          <li>
            <Link to="/book-rent-car" className="block py-2 px-4">Rent Car</Link>
          </li>
        </ul>
      </nav>
    </aside>
  );
};

export default LeftSidebar;
