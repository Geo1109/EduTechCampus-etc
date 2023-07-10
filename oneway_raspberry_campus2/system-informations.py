from psutil import cpu_count, virtual_memory, disk_usage
from platform import platform
from cpuinfo import get_cpu_info

print('The script is loading...')

System = platform()

CPUInfo = get_cpu_info()

Architecture = CPUInfo['arch']

CPU = CPUInfo['brand_raw']

print('')

print('=' * 20, 'Your system informations:', '=' * 20)

print('System:', System)

print('Architecture:', Architecture)

print('Processor:', CPU)

print('CPU Cores:', cpu_count())

print('Total RAM memory: {:,} GB' .format(virtual_memory().total))

print('Disk space: {:,} GB ({:,} free)' .format(disk_usage('/').total, disk_usage('/').free))

print('=' * 20, 'System informations', '=' * 20)