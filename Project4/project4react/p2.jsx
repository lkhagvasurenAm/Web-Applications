import React from 'react';
import ReactDOM from 'react-dom';

import States from './components/states/States';
import Header from './components/header/Header';

ReactDOM.render(
  <Header />,
  document.getElementById('header'),
);

ReactDOM.render(
  <States />,
  document.getElementById('reactapp'),
);
