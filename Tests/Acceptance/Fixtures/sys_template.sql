delete
from `sys_template`;

insert into `sys_template` (`pid`, `title`, `root`, `clear`, `include_static_file`)
values (1, 'Home', 1, 3, 'EXT:bootstrap_package/Configuration/TypoScript,EXT:xm_formcycle/Configuration/TypoScript');
