#Magento administrative tool

Used to perform administrative tasks on Magento instances remotely.

Designed to work with different versions of Magento.

Actual commands reside on Magento instances. When a new instance (context) is added, the tool loads list of supported commands from the instance. Whenever a user calls a command in tool, the request to the instance is made.

The tool itself only provides following commands:

   * ```magento instance:add [name] [url]``` - register a new managed remote instance
   * ```magento instance:remove [name]``` - unregister a managed instance from the tool instance list
   * ```magento instance:list``` - list registered remote instances
   * ```magento instance:update``` - load a list of commands supported by the instance
   * ```magento context:set [name]``` - select the default instance to be used in commands
   
### Magento instance endpoints

This tool only works with magento instances that support remote calls and metadata sharing

#### Security

As, remote call endpoints provide wide access to magento instance, they MUST be exposed through separate protected network interface.
   
### Evolution plan

* Add authentication
* Add install/deploy commands for standard environments (local, docker, vagrant, kubernetes)