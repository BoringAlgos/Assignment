import React, { useState, useEffect } from 'react';
import { makeApiCall } from '../utils/api';
import { useAuth } from '../context/AuthContext';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
const UsersListContent = () => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const { token } = useAuth();

  const markAvailability = async (userId, availability) => {
    try {
      const apiEndpoint = `api/update-availability/${userId}`;
      const headers = {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      };
      const data = await makeApiCall(apiEndpoint, {
        method: 'PUT',
        headers,
        body: JSON.stringify({ availability }),
      });
      toast.info('Availibility has been updated successfully!', {
        position: toast.POSITION.TOP_CENTER,
        autoClose: 3000,
      });
      // Update the local state after marking availability
      if (data.status === 'success') {
        setUsers((prevUsers) =>
          prevUsers.map((user) =>
            user.id === userId ? { ...user, availability: data.user.availability === 1 ? true : false } : user
          )
        );
        console.log('prevUsers:', users);
        console.log('Updated Users:', users.map((user) => (user.id === userId ? { ...user, availability: data.availability === 1 } : user)));
      }
    } catch (error) {
      toast.error('Please Try Again Later!', {
        position: toast.POSITION.TOP_CENTER,
        autoClose: 3000,
      });
      console.error('Error marking availability:', error);
    }
  };

  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const apiEndpoint = 'api/users';
        const headers = {
          Authorization: `Bearer ${token}`,
        };
        const data = await makeApiCall(apiEndpoint, { headers });
        setUsers(data.users)

      } catch (error) {
        console.error('Error fetching users:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchUsers();
  }, [token]);

  if (loading) {
    return <p>Loading...</p>;
  }

  return (
    <div className="ml-2 mt-1">
      <h2 className="text-2xl font-bold mb-4">Users List</h2>
      <div className="shadow-md mx-auto h-full overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                ID
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Role
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Area Code
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Availability
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {users.map((user) => (
              <tr key={user.id}>
                <td className="px-6 py-4 whitespace-nowrap">{user.id}</td>
                <td className="px-6 py-4 whitespace-nowrap">{user.name}</td>
                <td className="px-6 py-4 whitespace-nowrap">{user.email}</td>
                <td className="px-6 py-4 whitespace-nowrap">{user.roles.map((role) => role.name).join(', ')}</td>
                <td className="px-6 py-4 whitespace-nowrap">{user.area_pincode}</td>
                <td className="px-6 py-4 whitespace-nowrap">{user.availability ? 'Available' : 'Not Available'}</td>
                <td className="px-6 py-4 whitespace-nowrap">
                  <button
                    onClick={() => markAvailability(user.id, !user.availability)}
                    className={`px-4 py-2 ${user.availability ? 'bg-red-500' : 'bg-green-500'
                      } text-white rounded`}
                  >
                    {user.availability ? 'Mark Unavailable' : 'Mark Available'}
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default UsersListContent;
