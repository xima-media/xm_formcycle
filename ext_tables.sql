create table tt_content (
	tx_xmformcycle_form_id varchar(255) default '' not null,
	tx_xmformcycle_redirect_success int(11) default 0 not null,
	tx_xmformcycle_redirect_error int(11) default 0 not null,
	tx_xmformcycle_integration_mode int(11) default 0 not null,
	tx_xmformcycle_is_jquery int(11) default 0 not null,
	tx_xmformcycle_is_jquery_ui int(11) default 0 not null,
	tx_xmformcycle_additional_params varchar(255) default '' not null,
);
