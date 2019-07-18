
    const { createElement, render } = wp.element;
    const Test = () => {
        return createElement("div", null, "Testing");
      };
      
      render(
        createElement(Test, null),
        document.getElementById("test")
      );
