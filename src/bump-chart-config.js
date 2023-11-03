import React from 'react';
import { JsonForms } from '@jsonforms/react';
import { materialRenderers, materialCells } from '@jsonforms/material-renderers';
import { Provider } from 'react-redux';

import schema from './schema.json';
import uischema from './uischema.json';

function ConfigurableBumpChart() {

    const handleFormChange = (event) => {
        const updatedConfig = event.data; // Get the updated configuration from the form
        // Update the Bump Chart with the new configuration
        bump_chart.updateConfiguration(updatedConfig);
      };

  return (
    <Provider store={store}>
      <JsonForms
        schema={schema}
        uischema={uischema}
        renderers={materialRenderers}
        cells={materialCells}
        onChange={handleFormChange} //add the change event handler
      />
    </Provider>
  );
}

export default ConfigurableBumpChart;
