import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import DatePicker from 'react-datepicker';

const ProjectEdit = ({ projects, onSaveProject, setProjects }) => {
    const { id } = useParams();
    const project = projects.find((p) => p.id === parseInt(id)) || {};
    const [customFields, setCustomFields] = useState([]);

    const handleInputChange = (field, value) => {
        const updatedProject = { ...project, [field]: value };
        setProjects((prev) => prev.map((p) => (p.id === project.id ? updatedProject : p)));
        onSaveProject(updatedProject);
    };

    const handleCustomFieldChange = (index, key, value) => {
        const updatedFields = [...customFields];
        updatedFields[index] = { key, value };
        setCustomFields(updatedFields);
        const updatedProject = { ...project, ...Object.fromEntries(updatedFields.map(f => [f.key, f.value])) };
        setProjects((prev) => prev.map((p) => (p.id === project.id ? updatedProject : p)));
        onSaveProject(updatedProject);
    };

    const addCustomField = () => {
        setCustomFields([...customFields, { key: '', value: '' }]);
    };

    const validateDate = (date) => {
        return date && !isNaN(new Date(date).getTime()) ? date : '';
    };

    return (
        <div className="project-edit">
            <h1>Edit Project: {project.meeting_name || 'Unnamed'}</h1>
            <table className="project-table">
                <tbody>
                    <tr>
                        <td><strong>Meeting Name:</strong></td>
                        <td>
                            <input
                                type="text"
                                value={project.meeting_name || ''}
                                onChange={(e) => handleInputChange('meeting_name', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td>
                            <textarea
                                value={project.description || ''}
                                onChange={(e) => handleInputChange('description', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Client Name:</strong></td>
                        <td>
                            <input
                                type="text"
                                value={project.client_name || ''}
                                onChange={(e) => handleInputChange('client_name', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Job Number:</strong></td>
                        <td>
                            <input
                                type="text"
                                value={project.job_number || ''}
                                onChange={(e) => handleInputChange('job_number', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Start Date:</strong></td>
                        <td>
                            <DatePicker
                                selected={project.start_date ? new Date(project.start_date) : null}
                                onChange={(date) => handleInputChange('start_date', date ? date.toISOString().split('T')[0] : '')}
                                dateFormat="yyyy-MM-dd"
                                placeholderText="Select start date"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>End Date:</strong></td>
                        <td>
                            <DatePicker
                                selected={project.end_date ? new Date(project.end_date) : null}
                                onChange={(date) => {
                                    const newEndDate = date ? date.toISOString().split('T')[0] : '';
                                    if (newEndDate && project.start_date && new Date(newEndDate) < new Date(project.start_date)) {
                                        alert('End date must be after start date');
                                    } else {
                                        handleInputChange('end_date', newEndDate);
                                    }
                                }}
                                dateFormat="yyyy-MM-dd"
                                placeholderText="Select end date"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <select
                                value={project.status || ''}
                                onChange={(e) => handleInputChange('status', e.target.value)}
                            >
                                <option value="">Select status</option>
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="archived">Archived</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Meeting Type:</strong></td>
                        <td>
                            <input
                                type="text"
                                value={project.meeting_type || ''}
                                onChange={(e) => handleInputChange('meeting_type', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Attendees (Expected):</strong></td>
                        <td>
                            <input
                                type="number"
                                value={project.attendees_expected || ''}
                                onChange={(e) => handleInputChange('attendees_expected', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Attendees (Actual):</strong></td>
                        <td>
                            <input
                                type="number"
                                value={project.attendees_actual || ''}
                                onChange={(e) => handleInputChange('attendees_actual', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Est. Budget:</strong></td>
                        <td>
                            <input
                                type="text"
                                value={project.est_budget || ''}
                                onChange={(e) => handleInputChange('est_budget', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Cost per Attendee:</strong></td>
                        <td>
                            <input
                                type="text"
                                value={project.cost_per_attendee || ''}
                                onChange={(e) => handleInputChange('cost_per_attendee', e.target.value)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>PreCon Date:</strong></td>
                        <td>
                            <DatePicker
                                selected={project.precon_date ? new Date(project.precon_date) : null}
                                onChange={(date) => handleInputChange('precon_date', date ? date.toISOString().split('T')[0] : '')}
                                dateFormat="yyyy-MM-dd"
                                placeholderText="Select precon date"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Kickoff Date:</strong></td>
                        <td>
                            <DatePicker
                                selected={project.kickoff_date ? new Date(project.kickoff_date) : null}
                                onChange={(date) => handleInputChange('kickoff_date', date ? date.toISOString().split('T')[0] : '')}
                                dateFormat="yyyy-MM-dd"
                                placeholderText="Select kickoff date"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Resources:</strong></td>
                        <td>
                            <textarea
                                value={project.resources || ''}
                                onChange={(e) => handleInputChange('resources', e.target.value)}
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <h3>Custom Fields</h3>
            <table className="project-table">
                <tbody>
                    {customFields.map((field, index) => (
                        <tr key={index}>
                            <td>
                                <input
                                    type="text"
                                    value={field.key}
                                    onChange={(e) => handleCustomFieldChange(index, e.target.value, field.value)}
                                    placeholder="Key"
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    value={field.value}
                                    onChange={(e) => handleCustomFieldChange(index, field.key, e.target.value)}
                                    placeholder="Value"
                                />
                            </td>
                        </tr>
                    ))}
                    <tr>
                        <td colSpan="2">
                            <button onClick={addCustomField}>Add Custom Field</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    );
};

export default ProjectEdit;