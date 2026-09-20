import prefixCustomProperties from 'postcss-prefix-custom-properties';

export default {
    plugins: [
        prefixCustomProperties({
            prefix: 'tblr-',
            ignore: [/^--tblr-/, /^--bs-/, /^--fc-/, /^--gl-/, /^--litepicker-/, /^--plyr-/, /^--ts-/, '--section-bg'],
        }),
    ],
};
