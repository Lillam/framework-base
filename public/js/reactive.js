const dom = {};

const router = () => {
    const routes = {};

    const add = (path, handler) => {
        routes[path] = handler;
    };

    const navigate = (path) => {
        if (routes[path]) {
            routes[path]();
        }
    };
};

const app = () => {
    const router = router();

    const watch = (dom) => {

    };

    return {
        watch
    };
};
