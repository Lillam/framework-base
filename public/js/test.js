// the simplest test suite mechanism ever... this is basically how it's done.
const success = () => console.log(`     %c Pass`, 'color: green');

const successOrError = (is, { expected, actual }, error = null) => {
    if (is) {
        return success();
    }

    const errorMessage = error
        ? `Fail - ${expected} ${error} ${actual}`
        : `Fail - Actual: ${actual}, Expected: ${expected}`

    throw new Error(errorMessage);
};

const expect = (expected) => {
    return {
        toBe: (actual) => successOrError(
            expected === actual, { expected, actual }
        ),
        toBeNumber: () => successOrError(
            typeof expected === 'number', { expected }, 'Is not a number'
        ),
        toBeString: () => successOrError(
            typeof expected === 'string', { expected }, 'Is not a string'
        ),
        toBeGreaterThan: (actual) => successOrError(
            expected > actual, { expected, actual }, 'Is less than or equal to'
        ),
        toBeLessThan: (actual) => successOrError(
            expected < actual, { expected, actual }, 'Is greater than or equal to'
        ),
    };
};

const describe = (suiteName, callback) => {
    try {
        console.log(`Suite: %c ${suiteName}`, 'color: yellow');
        callback();
    } catch (error) {
        console.error(`\n ${error}`);
    }
};

const it = (testName, callback) => {
    try {
        console.log(`   test: %c ${testName}`, 'color: yellow');
        callback();
    } catch (error) {
        console.error(`     ${error} \n`);
        throw new Error('test run failed');
    }
};

// describe('My Tests Suite', () => {
//     it('1 === 1', () => {
//         expect(1).toBe(1);
//     });
//
//     it('1 is a number', () => {
//         expect(1).toBeNumber();
//     });
//
//     it('\'1\' is a string', () => {
//         expect('1').toBeString();
//     });
//
//     it('2 is greater than 1', () => {
//         expect(2).toBeGreaterThan(1);
//     });
//
//     it('1 is less than 2', () => {
//         expect(1).toBeLessThan(2);
//     });
// });