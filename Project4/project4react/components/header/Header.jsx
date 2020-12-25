import React from 'react';
import './Header.css';

/**
 * Define States, a React componment of CS142 project #4 problem #2.  The model
 * data for this view (the state names) is available
 * at window.cs142models.statesModel().
 */
class Header extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <div>
        I'm a header.
      </div>
    );
  }
}

export default Header;
