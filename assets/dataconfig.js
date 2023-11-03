import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import { JsonForms } from '@jsonforms/react';
import { materialRenderers, materialCells } from '@jsonforms/material-renderers';

import schema from './schema.json';
import uischema from './uischema.json';

const Form = (schema) => {
    const emptyData = {};
  
    const traverse = (currentSchema, data) => {
      for (const key in currentSchema.properties) {
        console.log(key)
        if (currentSchema.properties[key].type === 'object') {
          data[key] = {};
          traverse(currentSchema.properties[key], data[key]);
        } else {
          data[key] = "";
        }
      }
    };
  
    traverse(schema, emptyData);
    return emptyData;
  };


const App = () => {

    const [formData, setFormData] = useState(Form(schema));

    const handleFormChange = (event) => {
        const updatedData = event.data;
        console.log(updatedData);
        setFormData(updatedData);
    };

    const handleSubmit = () => {

        console.log("Form data submitted:", formData);

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