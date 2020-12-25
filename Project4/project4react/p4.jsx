import React from 'react';
import ReactDOM from 'react-dom';

import States from './components/states/States';
import Example from './components/example/Example';
import Header from './components/header/Header';

class Switcher extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      curPage: "States",
      buttonInfo: "Switch to Example"
    }

    this.handleSwitcherBound = this.handleSwitcher.bind(this);
  }

  handleSwitcher() {
    if (this.state.curPage === "States") {
      this.setState({curPage: "Example", buttonInfo: "Switch to Example"});
      ReactDOM.render(
        <Example />,
        document.getElementById('reactapp'),
      );
    } else {
      this.setState({curPage: "States", buttonInfo: "Switch to States"});
      ReactDOM.render(
        <States />,
        document.getElementById('reactapp'),
      );
    }
  }

  render() {
    return (
      <button onClick={this.handleSwitcherBound}>{this.state.buttonInfo}</button>
    );
  }
}

ReactDOM.render(
  <Header />,
  document.getElementById('header'),
);

ReactDOM.render(
  <Switcher />,
  document.getElementById('switching'),
);

ReactDOM.render(
  <States />,
  document.getElementById('reactapp'),
);