const domlyObserverHandlers = {};

const domlyObserver = new MutationObserver((mutations) => {
    mutations.forEach(mutation => {
        if (mutation.addedNodes.length) {
            mutation.addedNodes.forEach(node => {
                // here we are going to want to check ALL the added node's attributes,
                // class list
                // id
                // and so on and so forth.
                node.classList.forEach(className => {
                    if (domlyObserverHandlers[`.${className}`]) {
                        const { method, methodHandler } = domlyObserverHandlers[`.${className}`];
                        node.addEventListener(method, methodHandler);
                    }
                });
            });
        }
    });
});

const domly = (s) => {
    const element = document.querySelector(s);

    if (! element) {
        return null;
    }

    // if the type of s is an array, then we can return an array of document nodes
    // otherwise if this is a simple string, then we can return a single document node.
    //
    return {
        element,
        on: (method, selectorOrMethodHandler, methodHandler = null) => {
            // here we can check if this is a selector; if this happens to be a string we
            // can presume this to be a targer selector to find an html node.
            if (typeof selectorOrMethodHandler === 'string') {
                const onElement = element.querySelector(selectorOrMethodHandler);
                // here we would theoretically just bomb out because we don't have a method to apply
                // to the particular element in question;
                // doesn't exist either
                if (! method) {
                   return onElement;
                }

                // right here we can optimistically start to apply the method to the element when the
                // element finally gets created within the node... which means we can add an event listener
                // to check for node changes and if that node change contains the selector we are looking for
                // then immediately apply that to the element.
                if (! onElement) {
                    console.log('we should be here???');
                    domlyObserver.observe(element, { childList: true, subtree: true, attributes: true });
                    domlyObserverHandlers[selectorOrMethodHandler] = {
                        method,
                        methodHandler
                    };

                    // element.addEventListener('DOMNodeInserted', function (event) {
                    //     console.log('was inserted???');
                    //     // we are going to want to check classList or ID or attributes alike, however obviously
                    //     // the more we look to check the more resource we're going to use up.
                    //     if (event.target.classList.includes(selectorOrMethodHandler.replace('.'))) {
                    //         event.target.addEventListener(method, methodHandler);
                    //     }
                    // });
                }
            }
        }
    };
};

const addElementToFooter = () => {
    const ele = document.createElement('h2');
    ele.innerHTML = 'Hello, world!';
    ele.classList.add('titled');
    document.querySelector('.app-footer').appendChild(ele);
};

domly('.app-footer').on('click', '.titled', (e) => {
    console.log(e, 'from the button we added to footer, later...');
});
