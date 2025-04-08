import React from 'react';

const TemplateList = ({ templates }) => {
    return (
        <div className="template-list">
            <h2>Templates</h2>
            {templates.length === 0 ? (
                <p>No templates found.</p>
            ) : (
                <ul>
                    {templates.map((template) => (
                        <li key={template.id}>
                            {template.title} ({template.type})
                            {template.elements.length > 0 && (
                                <ul>
                                    {template.elements.map((element, index) => (
                                        <li key={index}>{element.key}: {element.value}</li>
                                    ))}
                                </ul>
                            )}
                        </li>
                    ))}
                </ul>
            )}
        </div>
    );
};

export default TemplateList;