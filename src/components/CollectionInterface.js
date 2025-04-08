// src/components/CollectionInterface.js
import React, { useState, useEffect } from 'react';

const CollectionInterface = ({ projectId }) => {
  const [blocks, setBlocks] = useState([]);
  const [templates, setTemplates] = useState([]);
  const [newBlockData, setNewBlockData] = useState({
    title: '',
    type: 'custom',
    elements: []
  });

  // Fetch project blocks and available templates
  useEffect(() => {
    if (projectId) {
      fetchBlocks();
      fetchTemplates();
    }
  }, [projectId]);

  const fetchBlocks = async () => {
    try {
      const response = await fetch(`/wp-json/project-info-manager/v1/projects/${projectId}/blocks`);
      const data = await response.json();
      setBlocks(data);
    } catch (error) {
      console.error('Error fetching blocks:', error);
    }
  };

  const fetchTemplates = async () => {
    try {
      const response = await fetch('/wp-json/project-info-manager/v1/block-templates');
      const data = await response.json();
      setTemplates(data);
    } catch (error) {
      console.error('Error fetching templates:', error);
    }
  };

  const handleAddBlock = async () => {
    try {
      const response = await fetch(`/wp-json/project-info-manager/v1/projects/${projectId}/blocks`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(newBlockData),
      });
      
      const newBlock = await response.json();
      setBlocks([...blocks, newBlock]);
      
      // Reset form
      setNewBlockData({
        title: '',
        type: 'custom',
        elements: []
      });
    } catch (error) {
      console.error('Error adding block:', error);
    }
  };

  const handleAddElement = () => {
    setNewBlockData({
      ...newBlockData,
      elements: [
        ...newBlockData.elements,
        { key: '', value: '', type: 'text' }
      ]
    });
  };

  const handleElementChange = (index, field, value) => {
    const updatedElements = [...newBlockData.elements];
    updatedElements[index] = {
      ...updatedElements[index],
      [field]: value
    };
    
    setNewBlockData({
      ...newBlockData,
      elements: updatedElements
    });
  };

  const applyTemplate = (templateId) => {
    const selectedTemplate = templates.find(t => t.id === templateId);
    if (selectedTemplate) {
      setNewBlockData({
        ...newBlockData,
        type: selectedTemplate.id,
        elements: [...selectedTemplate.elements]
      });
    }
  };

  return (
    <div className="collection-interface">
      <h2>Project Information Collection</h2>
      
      {/* Display existing blocks */}
      <div className="existing-blocks">
        <h3>Current Information Blocks</h3>
        {blocks.length === 0 ? (
          <p>No information blocks yet. Add your first one below!</p>
        ) : (
          <ul className="blocks-list">
            {blocks.map(block => (
              <li key={block.id} className="block-item">
                <h4>{block.title}</h4>
                <div className="elements-list">
                  {block.elements.map((element, idx) => (
                    <div key={idx} className="element-item">
                      <strong>{element.key}:</strong> {element.value}
                    </div>
                  ))}
                </div>
              </li>
            ))}
          </ul>
        )}
      </div>
      
      {/* Add new block form */}
      <div className="add-block-form">
        <h3>Add New Information</h3>
        
        <div className="form-row">
          <label>
            Block Title:
            <input 
              type="text" 
              value={newBlockData.title} 
              onChange={(e) => setNewBlockData({...newBlockData, title: e.target.value})}
              placeholder="E.g., Client Information, Project Scope, etc."
            />
          </label>
        </div>
        
        {/* Template selection */}
        {templates.length > 0 && (
          <div className="form-row">
            <label>
              Apply Template:
              <select onChange={(e) => applyTemplate(e.target.value)}>
                <option value="">-- Select a template --</option>
                {templates.map(template => (
                  <option key={template.id} value={template.id}>
                    {template.title}
                  </option>
                ))}
              </select>
            </label>
          </div>
        )}
        
        {/* Elements section */}
        <div className="elements-section">
          <h4>Elements</h4>
          {newBlockData.elements.map((element, index) => (
            <div key={index} className="element-form-row">
              <input
                type="text"
                value={element.key}
                onChange={(e) => handleElementChange(index, 'key', e.target.value)}
                placeholder="Field name"
              />
              <input
                type={element.type}
                value={element.value}
                onChange={(e) => handleElementChange(index, 'value', e.target.value)}
                placeholder="Value"
              />
              <select
                value={element.type}
                onChange={(e) => handleElementChange(index, 'type', e.target.value)}
              >
                <option value="text">Text</option>
                <option value="number">Number</option>
                <option value="date">Date</option>
                <option value="url">URL</option>
              </select>
            </div>
          ))}
          
          <button 
            type="button" 
            className="add-element-btn"
            onClick={handleAddElement}
          >
            Add Element
          </button>
        </div>
        
        <button 
          type="button" 
          className="save-block-btn"
          onClick={handleAddBlock}
          disabled={!newBlockData.title || newBlockData.elements.length === 0}
        >
          Save Information Block
        </button>
      </div>
      
      {/* Save as template option */}
      {newBlockData.elements.length > 0 && (
        <div className="save-template-section">
          <label>
            <input 
              type="checkbox" 
              onChange={(e) => setNewBlockData({...newBlockData, saveAsTemplate: e.target.checked})}
            />
            Save this structure as a reusable template
          </label>
          {newBlockData.saveAsTemplate && (
            <input
              type="text"
              placeholder="Template name"
              value={newBlockData.templateName || ''}
              onChange={(e) => setNewBlockData({...newBlockData, templateName: e.target.value})}
            />
          )}
        </div>
      )}
    </div>
  );
};

export default CollectionInterface;