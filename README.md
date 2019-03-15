# Magento administrative tool

Used to perform administrative tasks on Magento instances remotely.

Designed to work with different versions of Magento.

Actual commands reside on Magento instances. When a new instance (context) is added, the tool loads list of supported commands from the instance. Whenever a user calls a command in tool, the request to the instance is made.

The tool itself only provides following commands:

   * `./bin/magento instance:add [name] [type] [url]` - register a new managed remote instance
   * `./bin/magento instance:remove [name]` - unregister a managed instance from the tool instance list
   * `./bin/magento instance:list` - list registered remote instances
   * `./bin/magento instance:get` - show current context
   * `./bin/magento context:set [name]` - select the default instance to be used in commands
   
#### Remote types

Tool works with both remote and local calls.

### Add remote context:

```bash
./bin/magento context:add cloud remote <some_ssh_url>
```

### Add local context:

```bash
./bin/magento context:add local local <some_local_path>
```
   
#### Security

The tool is using SSH for remote calls.
   
### Evolution plan

* Add install/deploy commands for standard environments (local, docker, vagrant, kubernetes)
* Optimize IO operations
