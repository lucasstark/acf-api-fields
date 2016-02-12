# ACF API Relationship Field

Allows you to create relationships to posts from WP Rest API Endpoints. 

-----------------------


### Compatibility

This ACF field type is compatible with:
* ACF 5


### Installation / Use

1. Copy the `acf-api-fields` folder into your `wp-content/plugins` folder
2. Activate Advanced Custom Fields: API Fields plugin via the plugins admin page
3. Create a new field via ACF and select the API Relationship type
4. Enter in the endpoint to the remote API.   Example   http://example.com/wp-json/v2/wp/posts
5. Edit the object where you have included the field group.  You'll see a standard Relationship field, however the data will be populated from the remote API. 


The field type returns objects from get_field().   Example template file use:


```

<?php $remote_posts = get_field( 'remote_post_object' ); ?>
<?php if ( !empty( $remote_posts ) ): ?>

	<?php foreach ( $remote_posts as $remote_post ): ?>

		<h2><?php echo esc_html( $remote_post->get_title() ); ?></h2>
                <p><?php echo $remote_post->content->rendered; ?></p>

	<?php endforeach; ?>

<?php endif; ?>


```

The object which is returned from the API is similar to the object from the [Example Client Library](https://github.com/WP-API/client-php/blob/master/library/WPAPI/Post.php)
