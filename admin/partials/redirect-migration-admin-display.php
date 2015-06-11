<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    redirect_migration
 * @subpackage redirect_migration/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <?php screen_icon(); ?>
    <h2>Redirects</h2>
    <p>Below you will find the URL maps specified in the site.</p>
    <form action="" enctype="multipart/form-data" method="POST">
  
      <?php wp_nonce_field( 'upload_csv' ); ?>

      <table class="form-table">
        <tbody>
        <?php $this->print_text_input( 'Upload the matrix here', 'matrix', 'file'); ?>
        </tbody>
      </table>

      <input type="hidden" name="redirect_matrix_action" value="matrix_upload">
      
      <?php submit_button(); ?>

    </form>

    
    <form action="options.php" method="POST">
      <?php wp_nonce_field( 'upload_csv' ) ?>

      <table class="wp-list-table widefat fixed striped posts">
      	<thead>
        	<tr>
        		<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
              <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
              <input id="cb-select-all-1" type="checkbox">
            </th>
            <th scope="col" id="url_from" class="manage-column column-title sortable desc">
              <a href="#">
                <span>From</span><span class="sorting-indicator"></span>
              </a>
            </th>
            <th scope="col" id="url_to" class="manage-column column-author" style="">To</th>
            <th scope="col" id="status" class="manage-column column-categories" style="">Status</th>
            <th scope="col" id="active" class="manage-column column-tags" style="">Active</th>
          </tr>
      	</thead>

      	<tbody id="the-list">
          <?php foreach($maps as $map): ?>
      		<tr id="redirect-<?php echo $map->ID(); ?>" class="iedit author-self level-0 redirect-<?php echo $map->ID(); ?> type-redirect">
    				<th scope="row" class="check-column">
  						<label class="screen-reader-text" for="cb-select-<?php echo $map->ID(); ?>">Select Hello world!</label>
  			      <input id="cb-select-<?php echo $map->ID(); ?>" type="checkbox" name="redirect[]" value="<?php echo $map->ID(); ?>">
  					</th>
      			<td class="redirect-from column-from">
              <strong><a class="row-title" href="#"><?php echo $map->from(); ?></a></strong>
            </td>
            <td class="redirect-to column-to"><a href="<?php echo $map->to(); ?>" target="_blank"><?php echo $map->to(); ?></a></td>
            <td class="redirect-status column-status"><?php echo $map->status(); ?></td>
            <td class="redirect-active column-active"><a href="#"><?php echo $map->active() ? 'Active' : 'Inactive'; ?></a></td>
          </tr>
          <?php endforeach; ?>

      	</tbody>

      	<tfoot>
        	<tr>
        		<th scope="col" class="manage-column column-cb check-column" style="">
              <label class="screen-reader-text" for="cb-select-all-2">Select All</label>
              <input id="cb-select-all-2" type="checkbox">
            </th>
            <th scope="col" class="manage-column column-from sortable desc" style="">
              <a href="#"><span>From</span><span class="sorting-indicator"></span></a>
            </th>
            <th scope="col" class="manage-column column-to" style="">To</th>
            <th scope="col" class="manage-column column-status" style="">Status</th>
            <th scope="col" class="manage-column column-active" style="">Active</th>
          </tr>
      	</tfoot>

      </table>

      <?php submit_button(); ?>

    </form>
</div>
