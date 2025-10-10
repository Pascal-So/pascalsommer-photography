import $ from 'jquery';

export default () => {
    $('*[data-deletable-comment]').each((index, obj) => {
        console.log(obj);
        const name = $(obj).data('name');

        const message = `Do you really want to delete this comment by "${name}?"`;

        $(obj).click(e => {
            return confirm(message);
        });
    });

    $('*[data-deletable-post]').each((index, obj) => {
        const title = $(obj).data('title');

        const message = `Do you really want to delete the post "${title}?"`;

        $(obj).click(e => {
            return confirm(message);
        });
    });

    $('*[data-deletable-photo]').each((index, obj) => {
        const filename = $(obj).data('filename');

        const post = $(obj).data('post');

        const message = `Do you really want to delete the photo "${filename}?"`
            + ((post == null) ? '' : `\n\nWARNING: This photo is part of the post "${post}!"`);

        $(obj).click(e => {
            return confirm(message);
        });
    });

    $('*[data-deletable-tag]').each((index, obj) => {
        const name = $(obj).data('name');

        const photos_live = $(obj).data('photos-live');
        const photos = $(obj).data('photos');

        const message = `Do you really want to delete the tag "${name}?"`
            + ((photos > 0) ? `\n\nWARNING: This tag is on ${photos} photos, ${photos_live} of which are live!` : '');

        $(obj).click(e => {
            return confirm(message);
        });
    });
};
