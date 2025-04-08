import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import DatePicker from 'react-datepicker';


const ProjectList = ({ projects, onSaveProject, setProjects, role }) => {
    const [sortField, setSortField] = useState(null);
    const [sortDirection, setSortDirection] = useState('asc');

    const handleSort = (field) => {
        if (sortField === field) {
            setSortDirection(sortDirection === 'asc' ? 'desc' : 'asc');
        } else {
            setSortField(field);
            setSortDirection('asc');
        }
    };

    const handleInputChange = (projectId, field, value) => {
        const updatedProjects = projects.map((project) =>
            project.id === projectId || (!project.id && projectId === null)
                ? { ...project, [field]: value }
                : project
        );
        setProjects(updatedProjects); // Update local state without saving
    };

    const handleSave = (project) => {
        if (!project.meeting_name.trim()) {
            alert('Meeting name is required');
            return;
        }
        onSaveProject(project);
    };

    const handleDelete = (projectId) => {
        if (window.confirm('Are you sure you want to delete this project?')) {
            fetch(`/wp-json/project-info-manager/v1/projects/${projectId}`, {
                method: 'DELETE',
                credentials: 'include',
            })
                .then((res) => {
                    if (!res.ok) throw new Error('Failed to delete project');
                    setProjects((prev) => prev.filter((p) => p.id !== projectId));
                })
                .catch((err) => console.error('Delete error:', err));
        }
    };

    const sortedProjects = [...projects].sort((a, b) => {
        if (!sortField) return 0;

        const aValue = a[sortField] || '';
        const bValue = b[sortField] || '';

        if (sortField === 'start_date' || sortField === 'end_date') {
            const aDate = aValue ? new Date(aValue) : new Date(0);
            const bDate = bValue ? new Date(bValue) : new Date(0);
            return sortDirection === 'asc' ? aDate - bDate : bDate - aDate;
        }

        return sortDirection === 'asc'
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
    });

    return (
        <div className="project-list">
            <h1>Project Info Manager</h1>
            <p>Welcome, {role}!</p>

            <h2>Projects</h2>
            {projects.length === 0 ? (
                <p>No projects found.</p>
            ) : (
                <table className="project-table">
                    <thead>
                        <tr>
                            <th onClick={() => handleSort('meeting_name')}>
                                Meeting Name {sortField === 'meeting_name' && (sortDirection === 'asc' ? '↑' : '↓')}
                            </th>
                            <th onClick={() => handleSort('client_name')}>
                                Client Name {sortField === 'client_name' && (sortDirection === 'asc' ? '↑' : '↓')}
                            </th>
                            <th onClick={() => handleSort('start_date')}>
                                Start Date {sortField === 'start_date' && (sortDirection === 'asc' ? '↑' : '↓')}
                            </th>
                            <th onClick={() => handleSort('end_date')}>
                                End Date {sortField === 'end_date' && (sortDirection === 'asc' ? '↑' : '↓')}
                            </th>
                            <th onClick={() => handleSort('status')}>
                                Status {sortField === 'status' && (sortDirection === 'asc' ? '↑' : '↓')}
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {sortedProjects.map((project) => (
                            <tr key={project.id || 'new'}>
                                <td>
                                    <input
                                        type="text"
                                        value={project.meeting_name || ''}
                                        onChange={(e) => handleInputChange(project.id, 'meeting_name', e.target.value)}
                                        placeholder="Enter meeting name"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        value={project.client_name || ''}
                                        onChange={(e) => handleInputChange(project.id, 'client_name', e.target.value)}
                                        placeholder="Enter client name"
                                    />
                                </td>
                                <td>
                                    <DatePicker
                                        selected={project.start_date ? new Date(project.start_date) : null}
                                        onChange={(date) => handleInputChange(project.id, 'start_date', date ? date.toISOString().split('T')[0] : '')}
                                        dateFormat="yyyy-MM-dd"
                                        placeholderText="Select start date"
                                    />
                                </td>
                                <td>
                                    <DatePicker
                                        selected={project.end_date ? new Date(project.end_date) : null}
                                        onChange={(date) => {
                                            const newEndDate = date ? date.toISOString().split('T')[0] : '';
                                            if (newEndDate && project.start_date && new Date(newEndDate) < new Date(project.start_date)) {
                                                alert('End date must be after start date');
                                            } else {
                                                handleInputChange(project.id, 'end_date', newEndDate);
                                            }
                                        }}
                                        dateFormat="yyyy-MM-dd"
                                        placeholderText="Select end date"
                                    />
                                </td>
                                <td>
                                    <select
                                        value={project.status || ''}
                                        onChange={(e) => handleInputChange(project.id, 'status', e.target.value)}
                                    >
                                        <option value="">Select status</option>
                                        <option value="pending">Pending</option>
                                        <option value="active">Active</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </td>
                                <td>
                                    {project.id ? (
                                        <>
                                            <Link to={`/project/${project.id}`}>Edit</Link> | 
                                            <a href="#" onClick={(e) => { e.preventDefault(); handleDelete(project.id); }}>Delete</a>
                                        </>
                                    ) : (
                                        <button onClick={() => handleSave(project)}>Save</button>
                                    )}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            )}
        </div>
    );
};

export default ProjectList;