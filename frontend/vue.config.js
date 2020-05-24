module.exports = {
    devServer: {
        disableHostCheck: true,
        overlay: {
            warning: false
        },
        https: false,
        public: 'leoworkout.com:3000',
        port: 3000
    },
    lintOnSave: false
};
