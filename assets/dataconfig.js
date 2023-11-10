import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import { JsonForms } from '@jsonforms/react';
import { materialRenderers, materialCells } from '@jsonforms/material-renderers';
import schema from './schema.json';
import uischema from './uischema.json';
import defaultData from '../data/dataConfig.json';

const App = () => {

    const [formData, setFormData] = useState(defaultData);
    const handleFormChange = (event) => {
        const updatedData = event.data;
        for (const key in updatedData) {
            if (updatedData[key] === undefined) {
                // If a field is cleared, set it to an empty string
                updatedData[key] = "";
            }
        }
        setFormData(updatedData);
    };
    const handleSubmit = () => {
        fetch('/api/transfer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        })
            .then(response => response.json())
            .then(responseData => {
                console.log('API response:', responseData);
            })
            .catch(error => {
                console.error('Error sending data to the API:', error);
            });
    }
    return (
        <div>
            <JsonForms
                schema={schema}
                uischema={uischema}
                renderers={materialRenderers}
                cells={materialCells}
                onChange={handleFormChange}
                data={formData}
            />
            <button onClick={handleSubmit}>Submit</button>
        </div>
    );
};

const root = createRoot(document.getElementById('jsonforms'));
root.render(<App />);