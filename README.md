# Magento administrative tool

Used to perform administrative tasks on Magento instances remotely.

Designed to work with different versions of Magento.

Actual commands reside on Magento instances. When a new instance (context) is added, the tool loads list of supported commands from the instance. Whenever a user calls a command in tool, the request to the instance is made.

The tool itself only provides following commands:

   * `./bin/magento instance:add [name] [url] [key]` - register a new managed remote instance
   * `./bin/magento instance:remove [name]` - unregister a managed instance from the tool instance list
   * `./bin/magento instance:list` - list registered remote instances
   * `./bin/magento instance:get` - show current context
   * `./bin/magento context:set [name]` - select the default instance to be used in commands
   
### Magento instance endpoints

This tool only works with magento instances that support remote calls and metadata sharing.
Install and configure [Magento integration](https://github.com/shiftedreality/magento-tool-module) to be able to use this tool.

#### Security

As remote call endpoints provide wide access to magento instance, they MUST be exposed through separate protected network interface.
   
### Evolution plan

* Add install/deploy commands for standard environments (local, docker, vagrant, kubernetes)
* Optimize IO operations
