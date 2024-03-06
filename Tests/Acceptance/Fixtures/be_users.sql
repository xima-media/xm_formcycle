delete
from `be_users`;

insert into `be_users` (`uid`, `pid`, `username`, `admin`, `password`)
values (1, 0, 'admin', '1',
				'$argon2i$v=19$m=65536,t=16,p=1$YXZ4ZkExRmV0L0FtUFlHWg$8kOmOsOiNhRcWhtpyo/8e7Fzyk98SlVKvgoM798nmK8');
