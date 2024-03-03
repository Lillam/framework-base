const domlyObserverHandlers = {
    '#': {},
    '.': {}
};

const getObserverHandler = (selector) => {
    return domlyObserverHandlers[selector] ?? null;
}

const domlyObserver = new MutationObserver((mutations) => {
    mutations.forEach(mutation => {
        if (mutation.addedNodes.length) {
            mutation.addedNodes.forEach(node => {
                // here we are going to want to check ALL the added node's attributes,
                // class list
                // id
                // and so on and so forth.
                for (let attribute of node.attributes) {

                }

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

    // if we don't have an element then we can simply return null and not worry further.
    if (! element) {
        return null;
    }

    // if the type of s is an array, then we can return an array of document nodes
    // otherwise if this is a simple string, then we can return a single document node.
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
                // we're going to need this to be more specific with the domly observer... we
                if (! onElement) {
                    domlyObserver.observe(element, { childList: true, subtree: true, attributes: true });

                    domlyObserverHandlers[selectorOrMethodHandler] = {
                        method,
                        methodHandler
                    };
                }
            }
        }
    };
};

const addElementToFooter = () => {
    const ele = document.createElement('h2');
    const ele2 = document.createElement('span');
    const ele3 = document.createElement('a');
    ele3.innerHTML = 'yeet';
    ele.innerHTML = 'Hello, world!';

    ele2.append(ele3);
    ele.append(ele2);
    // ele.classList.add('titled');
    // ele.classList.add('test-again');
    // ele.setAttribute('data-test', 'test');
    ele.id = 'test';
    document.querySelector('.app-footer').appendChild(ele);
};

domly('.app-footer').on('click', '.titled', (e) => {
    console.log(e);
});

// @todo -> suppport the ability to add the same event on an identifier rather than a class.
domly('.app-footer').on('click', '#test', (e) => {
    console.log(e);
});

// @todo -> support the ability to add the event on a chained set of nodes... this would recursively
// need to create an observer to check for the updates that happen within (app-footer) that then listens for changes within (.titled) and
// then listens to span and then finally... a (if this anchor is inserted into the chain then we can decide to add this event onto it.)
domly('.app-footer').on('click', '.titled span a', (e) => {
    console.log(e);
});
