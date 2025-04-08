import React, { useState, useEffect } from 'react';
import { Route, Routes } from 'react-router-dom';
import ProjectList from './components/ProjectList';
import ProjectEdit from './components/ProjectEdit';
import './styles.css';

const App = () => {
    const [auth, setAuth] = useState({ isLoggedIn: false, role: null });
    const [projects, setProjects] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch('/wp-json/project-info-manager/v1/auth-status', {
            credentials: 'include',
        })
            .then((res) => res.json())
            .then((data) => {
                setAuth({
                    isLoggedIn: data.is_logged_in || false,
                    role: data.role || null,
                });
                if (data.is_logged_in) {
                    fetchProjects();
                } else {
                    setLoading(false);
                }
            })
            .catch((err) => setError('Failed to fetch auth status'));
    }, []);

    const fetchProjects = () => {
        fetch('/wp-json/project-info-manager/v1/projects', {
            credentials: 'include',
        })
            .then((res) => res.json())
            .then((data) => {
                if (Array.isArray(data)) {
                    setProjects([...data, { id: null, meeting_name: '', client_name: '', start_date: '', end_date: '', status: '' }]);
                }
                setLoading(false);
            })
            .catch((err) => setError('Failed to fetch projects'));
    };

    const saveProject = (project) => {
        const method = project.id ? 'PUT' : 'POST';
        const url = project.id
            ? `/wp-json/project-info-manager/v1/projects/${project.id}`
            : '/wp-json/project-info-manager/v1/projects';
        fetch(url, {
            method,
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(project),
        })
            .then((res) => {
                if (!res.ok) throw new Error('Failed to save project');
                return res.json();
            })
            .then((data) => {
                if (!project.id) {
                    setProjects((prev) =>
                        [...prev.filter((p) => p.id !== null), data, { id: null, meeting_name: '', client_name: '', start_date: '', end_date: '', status: '' }]
                    );
                } else {
                    setProjects((prev) => prev.map((p) => (p.id === project.id ? data : p)));
                }
            })
            .catch((err) => setError('Error saving project: ' + err.message));
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;
    if (!auth.isLoggedIn) return <div>Please log in to view projects.</div>;

    return (
        <div className="pim-app">
            <Routes>
                <Route
                    path="/"
                    element={<ProjectList projects={projects} onSaveProject={saveProject} setProjects={setProjects} role={auth.role} />}
                />
                <Route
                    path="/project/:id"
                    element={<ProjectEdit projects={projects} onSaveProject={saveProject} setProjects={setProjects} />}
                />
            </Routes>
        </div>
    );
};

export default App;