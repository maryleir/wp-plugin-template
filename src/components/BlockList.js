import React from 'react';

const BlockList = ({ blocks, projectId }) => {
    return (
        <div className="block-list">
            <h3>Blocks for Project #{projectId}</h3>
            {blocks.length === 0 ? (
                <p>No blocks found for this project.</p>
            ) : (
                <ul>
                    {blocks.map((block) => (
                        <li key={block.id}>
                            {block.title} (Type: {block.type})
                            {block.elements.length > 0 && (
                                <ul>
                                    {block.elements.map((element, index) => (
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

export default BlockList;